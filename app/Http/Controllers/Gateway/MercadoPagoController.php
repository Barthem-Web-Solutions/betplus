<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\AffiliateWithdraw;
use App\Models\SuitPayPayment;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Traits\Gateways\MercadoPagoTrait;
use App\Traits\Gateways\SuitpayTrait;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    use MercadoPagoTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackMethodPayment(Request $request)
    {
        $data = $request->all();
        \DB::table('debug')->insert(['text' => json_encode($request->all())]);

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function callbackMethod(Request $request)
    {
        $data = $request->all();

        if(isset($data['idTransaction']) && $data['typeTransaction'] == 'PIX') {
            if($data['statusTransaction'] == "PAID_OUT" || $data['statusTransaction'] == "PAYMENT_ACCEPT") {
                if(self::finalizePaymentMP($data['idTransaction'])) {
                    return response()->json([], 200);
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return null
     */
    public function getQRCodePix(Request $request)
    {
        return self::requestQrcodeMercadoPago($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransactionMP($request);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function confirmWithdrawalUser($id)
    {
        $withdrawal = Withdrawal::find($id);
        if(!empty($withdrawal)) {
            $suitpayment = SuitPayPayment::create([
                'withdrawal_id' => $withdrawal->id,
                'user_id'       => $withdrawal->user_id,
                'pix_key'       => $withdrawal->pix_key,
                'pix_type'      => $withdrawal->pix_type,
                'amount'        => $withdrawal->amount,
                'observation'   => 'suitpay',
            ]);

            if($suitpayment) {
                $parm = [
                    'pix_key'           => $withdrawal->pix_key,
                    'pix_type'          => $withdrawal->pix_type,
                    'amount'            => $withdrawal->amount,
                    'suitpayment_id'    => $suitpayment->id
                ];

                $resp = self::pixCashOutMP($parm);

                if($resp) {
                    $withdrawal->update(['status' => 1]);
                    Notification::make()
                        ->title('Saque solicitado')
                        ->body('Saque solicitado com sucesso')
                        ->success()
                        ->send();

                    return back();
                }else{
                    Notification::make()
                        ->title('Erro no saque')
                        ->body('Erro ao solicitar o saque')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function confirmWithdrawalAffiliate($id)
    {
        $withdrawal = AffiliateWithdraw::find($id);

        if(!empty($withdrawal)) {
            $suitpayment = SuitPayPayment::create([
                'withdrawal_id' => $withdrawal->id,
                'user_id'       => $withdrawal->user_id,
                'pix_key'       => $withdrawal->pix_key,
                'pix_type'      => $withdrawal->pix_type,
                'amount'        => $withdrawal->amount,
                'observation'   => 'mercadopago',
            ]);

            if($suitpayment) {
                $parm = [
                    'pix_key'           => $withdrawal->pix_key,
                    'pix_type'          => $withdrawal->pix_type,
                    'amount'            => $withdrawal->amount,
                    'suitpayment_id'    => $suitpayment->id
                ];

                $resp = self::pixCashOutMP($parm);

                if($resp) {
                    $withdrawal->update(['status' => 1]);
                    Notification::make()
                        ->title('Saque solicitado')
                        ->body('Saque solicitado com sucesso')
                        ->success()
                        ->send();

                    return back();
                }else{
                    Notification::make()
                        ->title('Erro no saque')
                        ->body('Erro ao solicitar o saque')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function withdrawalFromModal($id, $action)
    {
        if($action == 'user') {
            return $this->confirmWithdrawalUser($id);
        }

        if($action == 'affiliate') {
            return $this->confirmWithdrawalAffiliate($id);
        }


    }

    /**
     * Cancel Withdrawal
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelWithdrawalFromModal($id, $action)
    {
        if($action == 'user') {
            return $this->cancelWithdrawalUser($id);
        }

        if($action == 'affiliate') {
            return $this->cancelWithdrawalAffiliate($id);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function cancelWithdrawalAffiliate($id)
    {
        $withdrawal = AffiliateWithdraw::find($id);
        if(!empty($withdrawal)) {
            $wallet = Wallet::where('user_id', $withdrawal->user_id)
                ->where('currency', $withdrawal->currency)
                ->first();

            if(!empty($wallet)) {
                $wallet->increment('refer_rewards', $withdrawal->amount);

                $withdrawal->update(['status' => 2]);
                Notification::make()
                    ->title('Saque cancelado')
                    ->body('Saque cancelado com sucesso')
                    ->success()
                    ->send();

                return back();
            }
            return back();
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function cancelWithdrawalUser($id)
    {
        $withdrawal = Withdrawal::find($id);
        if(!empty($withdrawal)) {
            $wallet = Wallet::where('user_id', $withdrawal->user_id)
                ->where('currency', $withdrawal->currency)
                ->first();

            if(!empty($wallet)) {
                $wallet->increment('balance_withdrawal', $withdrawal->amount);

                $withdrawal->update(['status' => 2]);
                Notification::make()
                    ->title('Saque cancelado')
                    ->body('Saque cancelado com sucesso')
                    ->success()
                    ->send();

                return back();
            }
            return back();
        }
        return back();
    }
}
