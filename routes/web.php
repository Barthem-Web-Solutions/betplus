<?php

use App\Models\Game;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|Sme
*/
Route::get('clear', function() {
    Artisan::command('clear', function () {
        Artisan::call('optimize:clear');
       return back();
    });

    return back();
});

// GAMES PROVIDER
include_once(__DIR__ . '/groups/provider/games.php');
include_once(__DIR__ . '/groups/provider/venix.php');
include_once(__DIR__ . '/groups/provider/salsa.php');


// GATEWAYS
include_once(__DIR__ . '/groups/gateways/digitopay.php');
include_once(__DIR__ . '/groups/gateways/sharkpay.php');
include_once(__DIR__ . '/groups/gateways/mercadopago.php');
include_once(__DIR__ . '/groups/gateways/suitpay.php');

/// SOCIAL
include_once(__DIR__ . '/groups/auth/social.php');

// APP
include_once(__DIR__ . '/groups/layouts/app.php');

