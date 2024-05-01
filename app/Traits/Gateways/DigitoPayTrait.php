<?php

namespace App\Traits\Gateways;

use App\Models\AffiliateHistory;
use App\Models\Deposit;
use App\Models\DigitoPayPayment;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\NewDepositNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Core as Helper;

trait DigitoPayTrait
{
    /**
     * @var $uri
     * @var $clienteId
     * @var $clienteSecret
     */
    protected static string $bearerToken;
    protected static string $uri;

    /**
     * Generate Credentials
     * Metodo para gerar credenciais
     */
    private static function digitoPayGenerateCredentials()
    {
        $setting = Gateway::first();
        if(!empty($setting)) {
            self::$uri = $setting->getAttributes()['digitopay_uri'];
            $clientId = $setting->getAttributes()['digitopay_cliente_id'];
            $clienteSecret = $setting->getAttributes()['digitopay_cliente_secret'];

            self::$bearerToken = Http::post(self::$uri.'api/token/api',
                [
                    "clientId" => $clientId,
                    "secret" => $clienteSecret
                ])->object()->accessToken;
        }
    }

    /**
     * Request QRCODE
     * Metodo para solicitar uma QRCODE PIX
 * @return array
     */
    public static function digitoPayRequestQrcode($request)
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

        self::digitoPayGenerateCredentials();

        $response = Http::withToken(self::$bearerToken)->post(self::$uri.'api/deposit', [
            "dueDate" => Carbon::now()->addDay(),
            "paymentOptions" => ["PIX"],
            "person" => [
                "cpf" => Helper::soNumero($request->cpf),
                "name" => auth('api')->user()->name,
            ],
            "value" => $request->amount
        ]);

        if($response->successful()) {
            $responseData = $response->json();
            self::digitoPayGenerateTransaction($responseData['id'], Helper::amountPrepare($request->amount), false); /// gerando historico
            self::digitoPayGenerateDeposit($responseData['id'], Helper::amountPrepare($request->amount)); /// gerando deposito

            return [
                'status' => true,
                'idTransaction' => $responseData['id'],
                'qrcode' => $responseData['pixCopiaECola']
            ];
        }

        return [
            'status' => false,
        ];
    }

    /**
     * Consult Status Transaction
     * Consultar o status da transação

     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function digitoPayConsultStatusTransaction($request)
    {
        self::digitoPayGenerateCredentials();

        $response = Http::withToken(self::$bearerToken)->get(self::$uri.'api/status/'.$request->idTransaction);
        if($response->successful()) {
            $responseData = $response->object();

            if($responseData == "REALIZADO")
            {
                if(self::digitoPayFinalizePayment($request->idTransaction)) {
                    return response()->json(['status' => 'PAID']);
                }

                return response()->json(['status' => $responseData], 400);
            }

            return response()->json(['status' => $responseData], 400);

        }

        return response()->json(['status' => 'ERROR'], 400);
    }

    /**
     * @param $idTransaction

     * @return bool
     */
    public static function digitoPayFinalizePayment($idTransaction) : bool
    {
        $transaction = Transaction::where('payment_id', $idTransaction)->where('status', 0)->first();
        $setting = \Helper::getSetting();

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

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * * Use Digitopay - o melhor gateway de pagamentos para sua plataforma - 048 98814-2566
     * @return void
     */
    private static function digitoPayGenerateDeposit($idTransaction, $amount)
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
     * * Use Digitopay - o melhor gateway de pagamentos para sua plataforma - 048 98814-2566
     * @return void
     */
    private static function digitoPayGenerateTransaction($idTransaction, $amount, $accept_bonus)
    {
        $setting = \Helper::getSetting();

        Transaction::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'payment_method' => 'pix',
            'price' => $amount,
            'currency' => $setting->currency_code,
            'accept_bonus' => $accept_bonus,
            'status' => 0
        ]);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public static function digitoPayPixCashOut(array $array): bool
    {
        self::digitoPayGenerateCredentials();

        $response = Http::withToken(self::$bearerToken)->post(self::$uri.'api/withdraw', [
            "paymentOptions" => ["PIX"],
            "person" => [
                "pixKeyTypes" => self::FormatPixType($array['pix_type']),
                "pixKey" => $array['pix_key']
            ],
            "value" => $array['amount']
        ]);

        if($response->successful()) {
            $responseData = $response->json();
            $digitoPayPayment = DigitoPayPayment::lockForUpdate()->find($array['digitoPayPayment_id']);
            if(!empty($digitoPayPayment)) {
                if($digitoPayPayment->update(['status' => 1, 'payment_id' => $responseData['id']])) {
                    return true;
                }
                return false;
            }
            return $responseData['success'];
        }
        return false;
    }

    /**
     * @param $type
     * @return string|void
     */
    private static function FormatPixType($type)
    {
        switch ($type) {
            case 'email':
                return 'EMAIL';
            case 'document' && strlen($type) == 11:
                return 'CPF';
            case 'document' && strlen($type) == 14:
                return 'CNPJ';
            case 'randomKey':
                return 'EVP';
            case 'phoneNumber':
                return 'PHONE';
        }
    }

}
