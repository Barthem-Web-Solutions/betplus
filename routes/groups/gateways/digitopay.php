<?php


use App\Http\Controllers\Gateway\DigitoPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('digitopay')
    ->group(function ()
    {
        Route::post('callback', [DigitoPayController::class, 'callbackMethod']);
        Route::post('payment', [DigitoPayController::class, 'callbackMethodPayment']);

        Route::middleware(['admin.filament'])
            ->group(function ()
            {
                Route::get('withdrawal/{id}/{action}', [DigitoPayController::class, 'withdrawalFromModal'])->name('digitopay.withdrawal');
                Route::get('cancelwithdrawal/{id}/{action}', [DigitoPayController::class, 'cancelWithdrawalFromModal'])->name('digitopay.cancelwithdrawal');
            });
    });

