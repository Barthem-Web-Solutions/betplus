<?php

namespace App\Traits\Gateways;

use App\Models\AffiliateHistory;
use App\Models\Deposit;
use App\Models\GamesKey;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\SuitPayPayment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\NewDepositNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Core as Helper;

trait SharkPayTrait
{
    protected static $uriSharkPay = 'https://sharkpay.com.br/api/';

    /**
     * Generate Credentials
     * Metodo para gerar credenciais
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateCredentialsSharkPay()
    {
        $gateway = Gateway::first();
        $response = Http::withBasicAuth($gateway->getAttributes()['public_key'], $gateway->getAttributes()['private_key'])->post(self::$uriSharkPay.'auth');
        if($response->successful()) {
            $json = $response->json();
            $token = $json['success']['token'];

            if($token) {
                return $token;
            }

            return false;
        }
    }

    /**
     * Request QRCODE
     * Metodo para solicitar uma QRCODE PIX
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return array
     */
    public static function requestQrcodeSharkPay($request)
    {
        $setting = \Helper::getSetting();
        $rules = [
            'amount' => ['required', 'max:'.$setting->min_deposit, 'max:'.$setting->max_deposit],
            'cpf'    => ['required', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($token = self::generateCredentialsSharkPay()) {
            $order = Transaction::create([
                'payment_id' => time(),
                'user_id' => auth('api')->user()->id,
                'payment_method' => 'pix',
                'price' => $request->amount,
                'currency' => $setting->currency_code,
                'accept_bonus' => $request->accept_bonus,
                'status' => 0
            ]);

            if($order) {
                $params = [
                    'amount'     => \Helper::amountPrepare($request->amount),
                    'email'      => auth('api')->user()->email,
                    'quantity'   => 1,
                    'discount'   => 0,
                    'invoice_no' => $order->id,
                    'due_date'   => Carbon::now(),
                    'tax'        => 0,
                    'notes'      => 'Recarga de R$'.$request->amount,
                    'item_name'  => 'Recarga',
                    'document'   => \Helper::soNumero($request->cpf),
                    'client'     => auth('api')->user()->name,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '. $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post(self::$uriSharkPay.'pix/create', $params);

                if($response->successful()) {
                    $pix = $response->json();
                    $txid = $pix['invoice']['txid'];
                    $reference = $pix['invoice']['reference'];

                    $order->update(['payment_id' => $txid, 'reference' => $reference]);

                    return [
                        'status' => true,
                        'idTransaction' => $txid,
                        'qrcode' => $pix['invoice']['copy']
                    ];
                }
                return [
                    'status' => false,
                ];
            }
            return [
                'status' => false,
            ];
        }
    }

    /**
     * Consult Status Transaction
     * Consultar o status da transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function consultStatusTransactionSharkpay($request)
    {
        if($token = self::generateCredentialsSharkPay()) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '. $token,
                'Accept' => 'application/json',
            ])->get(self::$uriSharkPay.'pix/txid/'.$request->idTransaction);

            if($response->successful()) {
                $json = $response->json();
                if($json) {
                    $invoice = $json['invoice'];

                    if($invoice['status'] == 1) {
                        if(self::finalizePaymentSharpay($request->idTransaction)) {
                            return response()->json(['status' => 'PAID']);
                        }
                    }
                    return response()->json(['status' => 'PENDING']);
                }
                return response()->json(['status' => 'PENDING']);
            }
            return response()->json(['status' => 'PENDING']);
        }
    }

    /**
     * @param $idTransaction
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return bool
     */
    public static function finalizePaymentSharpay($idTransaction) : bool
    {
        $transaction = Transaction::where('payment_id', $idTransaction)->where('status', 0)->first();
        $setting = \Helper::getSetting();

        if(!empty($transaction)) {
            $user = User::find($transaction->user_id);

            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            if(!empty($wallet)) {
                $setting = Setting::first();

                /// verifica se é o primeiro deposito, verifica as transações, somente se for transações concluidas
                $checkTransactions = Transaction::where('user_id', $transaction->user_id)
                    ->where('status', 1)
                    ->count();

                if($checkTransactions == 0 || empty($checkTransactions)) {
                    if($transaction->accept_bonus) {
                        /// pagar o bonus
                        $bonus = Helper::porcentagem_xn($setting->initial_bonus, $transaction->price);
                        $wallet->increment('balance_bonus', $bonus);

                        if(!$setting->disable_rollover) {
                            $wallet->update(['balance_bonus_rollover' => $bonus * $setting->rollover]);
                        }
                    }
                }

                /// rollover deposito
                if(!$setting->disable_rollover) {
                    $wallet->update(['balance_deposit_rollover' => $transaction->price * intval($setting->rollover_deposit)]);
                }

                /// acumular bonus
                Helper::payBonusVip($wallet, $transaction->price);

                if($setting->disable_rollover) {
                    $wallet->increment('balance_withdrawal', $transaction->price);
                }else{
                    $wallet->increment('balance', $transaction->price);
                }

                if($transaction->update(['status' => 1])) {
                    $deposit = Deposit::where('payment_id', $idTransaction)->where('status', 0)->first();
                    if(!empty($deposit)) {

                        /// fazer o deposito em cpa
                        $affHistoryCPA = AffiliateHistory::where('user_id', $user->id)
                            ->where('commission_type', 'cpa')
                            //->where('deposited', 1)
                            //->where('status', 0)
                            ->first();

                        if(!empty($affHistoryCPA)) {
                            /// faz uma soma de depositos feitos pelo indicado
                            $affHistoryCPA->increment('deposited_amount', $transaction->price);

                            /// verifcia se já pode receber o cpa
                            $sponsorCpa = User::find($user->inviter);
                            if(!empty($sponsorCpa) && $affHistoryCPA->status == 'pendente') {
                                if($affHistoryCPA->deposited_amount >= $sponsorCpa->affiliate_baseline || $deposit->amount >= $sponsorCpa->affiliate_baseline) {
                                    $walletCpa = Wallet::where('user_id', $affHistoryCPA->inviter)->first();
                                    if(!empty($walletCpa)) {

                                        /// paga o valor de CPA
                                        $walletCpa->increment('refer_rewards', $sponsorCpa->affiliate_cpa); /// coloca a comissão
                                        $affHistoryCPA->update(['status' => 1, 'commission_paid' => $sponsorCpa->affiliate_cpa]); /// desativa cpa
                                    }
                                }else{
                                    $affHistoryCPA->update(['deposited_amount' => $transaction->price]);
                                }
                            }
                        }

                        if($deposit->update(['status' => 1])) {
                            $admins = User::where('role_id', 0)->get();
                            foreach ($admins as $admin) {
                                $admin->notify(new NewDepositNotification($user->name, $transaction->price));
                            }

                            return true;
                        }
                        return false;
                    }
                    return false;
                }

                return false;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateDepositSharkPay($idTransaction, $amount)
    {
        $userId = auth('api')->user()->id;
        $wallet = Wallet::where('user_id', $userId)->first();

        Deposit::create([
            'payment_id'=> $idTransaction,
            'user_id'   => $userId,
            'amount'    => $amount,
            'type'      => 'pix',
            'currency'  => $wallet->currency,
            'symbol'    => $wallet->symbol,
            'status'    => 0
        ]);
    }

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateTransactionSharkPay($idTransaction, $amount)
    {
        $setting = \Helper::getSetting();

        Transaction::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'payment_method' => 'pix',
            'price' => $amount,
            'currency' => $setting->currency_code,
            'status' => 0
        ]);
    }

    /**
     * @param $request
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return \Illuminate\Http\JsonResponse|void
     */
    public static function pixCashOutSharkPay(array $array): bool
    {
        if($token = self::generateCredentialsSharkPay()) {
            $suitPayPayment = SuitPayPayment::lockForUpdate()->find($array['suitpayment_id']);
            if(!empty($suitPayPayment)) {
                $user = User::find($suitPayPayment->user_id);
                $params = [
                    'amount'        => \Helper::amountPrepare($array['amount']),
                    'pixkey'        => $array['pix_key'],
                    'keytype'       => \Helper::checkPixKeyTypeSharkPay($array['pix_key']),
                    'document'      => $user->cpf,
                    'email'         => $user->email,
                    'description'   => $user->name,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '. $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post(self::$uriSharkPay.'pixout/create', $params);

                if($response->successful()) {
                    $responseData = $response->json();

                    if(isset($responseData['withdraw']) && $responseData['withdraw']['status'] == 'PAID') {
                        if(!empty($suitPayPayment)) {
                            if($suitPayPayment->update(['status' => 1, 'payment_id' => $responseData['withdraw']['reference']])) {
                                return true;
                            }
                            return false;
                        }
                        return false;
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
    }
}
