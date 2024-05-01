<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Traits\Gateways\DigitoPayTrait;
use App\Traits\Gateways\MercadoPagoTrait;
use App\Traits\Gateways\SharkPayTrait;
use App\Traits\Gateways\SuitpayTrait;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    use SuitpayTrait, SharkPayTrait, MercadoPagoTrait, DigitoPayTrait;

    /**
     * @param Request $request
     * @return array|false[]
     */
    public function submitPayment(Request $request)
    {
        \Log::info($request->gateway);
        switch ($request->gateway) {
            case 'digitopay':
                return self::digitoPayRequestQrcode($request);
            case 'mercadopago':
                return self::requestQrcodeMercadoPago($request);
            case 'sharkpay':
                return self::requestQrcodeSharkPay($request);
            case 'suitpay':
                return self::requestQrcode($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransaction($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::whereUserId(auth('api')->id())->paginate();
        return response()->json(['deposits' => $deposits], 200);
    }

}
