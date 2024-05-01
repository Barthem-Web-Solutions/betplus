<?php


use App\Http\Controllers\Gateway\MercadoPagoController;
use Illuminate\Support\Facades\Route;


Route::prefix('mercadopago')
    ->group(function ()
    {
        Route::post('callback', [MercadoPagoController::class, 'callbackMethod']);
        Route::post('payment/{id}', [MercadoPagoController::class, 'callbackMethodPayment']);

        Route::middleware(['admin.filament'])
            ->group(function ()
            {
                Route::get('withdrawal/{id}/{action}', [MercadoPagoController::class, 'withdrawalFromModal'])->name('mercadopago.withdrawal');
                Route::get('cancelwithdrawal/{id}/{action}', [MercadoPagoController::class, 'cancelWithdrawalFromModal'])->name('mercadopago.cancelwithdrawal');
            });
    });
