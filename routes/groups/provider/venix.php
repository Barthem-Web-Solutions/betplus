<?php

use App\Http\Controllers\Api\Games\GameController;
use Illuminate\Support\Facades\Route;

Route::post('venix_api', [GameController::class, 'webhookVeniXMethod']);
