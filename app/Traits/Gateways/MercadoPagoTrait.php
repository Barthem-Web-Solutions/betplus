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

trait MercadoPagoTrait
{

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateDepositMP($idTransaction, $amount)
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
    private static function generateTransactionMP($idTransaction, $amount)
    {
        $setting = Helper::getSetting();

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
     * Request QRCODE
     * Metodo para solicitar uma QRCODE PIX
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return array
     */
    public static function requestQrcodeMercadoPago($request)
    {
        $setting = Helper::getSetting();
        $rules = [
            'amount' => ['required', 'max:'.$setting->min_deposit, 'max:'.$setting->max_deposit],
            'cpf'    => ['required', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gateway = Gateway::first();
        $stringGenerate = Helper::generateRandomString(15);

        $token = Helper::MakeToken([
            'total' => $request->amount,
            'qty' => 1,
            'user_id' => auth('api')->user()->id
        ]);

        $response = Http::withHeaders([
            'X-Idempotency-Key' => $token,
            'Authorization' => 'Bearer ' . $gateway->mp_access_token,
        ])->post('https://api.mercadopago.com/v1/payments', [
            "transaction_amount" => floatval($request->amount),
            "description" => 'Pagamento',
            "payment_method_id" => "pix",
            "notification_url" => url('mercadopago/callback'),
            "external_reference" => $stringGenerate,
            "payer" => [
                "email" => auth('api')->user()->email,
                "first_name" => auth('api')->user()->name,
                "last_name" => auth('api')->user()->name,
                "identification" => [
                    "type" => "CPF",
                    "number" => Helper::soNumero($request->cpf)
                ]
            ]
        ]);

        if($response->successful()) {
            $resp = $response->json();

            if(isset($resp['id']) && !empty($resp['id'])) {
                self::generateTransactionMP($resp['id'], Helper::amountPrepare($request->amount), $request->accept_bonus); /// gerando historico
                self::generateDepositMP($resp['id'], Helper::amountPrepare($request->amount)); /// gerando deposito

                $transactionData = $resp['point_of_interaction']['transaction_data'];
                return [
                    'status' => true,
                    'qrcode' => $transactionData['qr_code'],
                    'idTransaction' => $resp['id'],
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

    /**
     * Update the specified resource in storage.
     */
    public function mercadoPagoCallback($request)
    {
        $gateway = Gateway::first();

        if($request->topic === 'payment') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $gateway->mp_access_token,
            ])->get('https://api.mercadopago.com/v1/payments/' .$request->id);

            if($response->successful()) {
                $mp = $response->json();
                $orderCheck = Transaction::where('payment_id', $mp['id'])->where('status', 0)->first();

                if(!empty($orderCheck)) {

                    switch ($mp['status']) {
                        case 'approved':
                            $orderCheck->update(['status' => 1]);
                            self::finalizePaymentMP($mp['id']);
                            return response()->json(['status' => true, 'message' => 'sucesso']);
                        case 'rejected':
                            return response()->json(['status' => true, 'message' => 'sucesso']);
                        case 'cancelled':
                            return response()->json(['status' => true, 'message' => 'sucesso']);
                        case 'refunded':
                            return response()->json(['status' => true, 'message' => 'sucesso']);
                        default:
                            break;
                    }
                }
            }
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultStatusTransactionMP($request)
    {
        $gateway = Gateway::first();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $gateway->mp_access_token,
        ])->get('https://api.mercadopago.com/v1/payments/'.$request->idTransaction,);

        if($response->successful()) {
            $mp = $response->json();

            switch ($mp['status']) {
                case 'approved':
                    if(self::finalizePaymentMP($request->idTransaction)) {
                        return response()->json(['status' => 'PAID']);
                    }
                    return response()->json(['status' => false], 400);
                default:
                    return response()->json(['status' => false], 400);
            }
            return response()->json(['status' => false], 400);
        }
        return response()->json(['status' => false], 400);
    }

    /**
     * @param $idTransaction
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return bool
     */
    public static function finalizePaymentMP($idTransaction) : bool
    {
        $transaction = Transaction::where('payment_id', $idTransaction)->where('status', 0)->first();
        $setting = Helper::getSetting();

        if(!empty($transaction)) {
            $user = User::find($transaction->user_id);

            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            if(!empty($wallet)) {

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
                if($setting->disable_rollover == false) {
                    $wallet->increment('balance_deposit_rollover', ($transaction->price * intval($setting->rollover_deposit)));
                }

                /// acumular bonus
                Helper::payBonusVip($wallet, $transaction->price);

                /// quando tiver desativado o rollover, ele manda o dinheiro depositado direto pra carteira de saque
                if($setting->disable_rollover) {
                    $wallet->increment('balance_withdrawal', $transaction->price); /// carteira de saque
                }else{
                    $wallet->increment('balance', $transaction->price); /// carteira de jogos, não permite sacar
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

                            /// verifica se foi pago ou nnão
                            if(!empty($sponsorCpa) && $affHistoryCPA->status == 'pendente') {

                                if($affHistoryCPA->deposited_amount >= $sponsorCpa->affiliate_baseline || $deposit->amount >= $sponsorCpa->affiliate_baseline) {
                                    $walletCpa = Wallet::where('user_id', $affHistoryCPA->inviter)->first();
                                    if(!empty($walletCpa)) {

                                        /// paga o valor de CPA
                                        $walletCpa->increment('refer_rewards', $sponsorCpa->affiliate_cpa); /// coloca a comissão
                                        $affHistoryCPA->update(['status' => 1, 'commission_paid' => $sponsorCpa->affiliate_cpa]); /// desativa cpa
                                    }
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

    public static function pixCashOutMP($params)
    {

    }

}
