<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'digitopay/*',
        'slotegrator/*',
        'suitpay/*',
        'vgames/*',
        'webhooks/*',
        'salsa/*',
        'fivers/*',
        'bspay/*',
        'gold_api',
        'gold_api/*',
        'kagaming/*',
        'vibra/*',
        'cron/*',
        'venix_api',
        'venix_api/*',
        'ever/*',
        'ever',
        'playgaming',
        'playgaming/*',
        'playigaming_api',
        'playigaming_api/*',
        'mercadopago/*',
        'mercadopago'
    ];
}
