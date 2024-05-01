<?php

use App\Http\Controllers\Gateway\DigitoPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('digitopay')
    ->group(function () {
        Route::post('qrcode-pix', [DigitoPayController::class, 'getQRCodePix']);
        Route::post('consult-status-transaction', [DigitoPayController::class, 'consultStatusTransactionPix']);
    });



