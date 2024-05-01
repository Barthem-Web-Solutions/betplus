<?php


use App\Http\Controllers\Gateway\SharkPayController;
use Illuminate\Support\Facades\Route;


Route::prefix('sharkpay')
    ->group(function ()
    {
        Route::post('callback', [SharkPayController::class, 'callbackMethod']);
        Route::post('payment', [SharkPayController::class, 'callbackMethodPayment']);

        Route::middleware(['admin.filament'])
            ->group(function ()
            {
                Route::get('withdrawal/{id}/{action}', [SharkPayController::class, 'withdrawalFromModal'])->name('sharkpay.withdrawal');
                Route::get('cancelwithdrawal/{id}/{action}', [SharkPayController::class, 'cancelWithdrawalFromModal'])->name('sharkpay.cancelwithdrawal');
            });
    });

