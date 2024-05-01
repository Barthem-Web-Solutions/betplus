<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use App\Models\Wallet;
use Illuminate\Http\Request;

class VipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nivelToday = Vip::whereNull('bet_period')->get();
        $nivelWeekly = Vip::where('bet_period', 'weekly')->get();
        $nivelMonthly = Vip::where('bet_period', 'monthly')->get();
        $nivelYearly = Vip::where('bet_period', 'yearly')->get();

        $wallet = Wallet::whereUserId(auth('api')->id())->first();
        $nextVipLevel = Vip::where('bet_level', '>', $wallet->vip_level)
            ->orderBy('bet_level')
            ->first();

        return response()->json([
            'nivelToday' => $nivelToday,
            'nivelWeekly' => $nivelWeekly,
            'nivelMonthly' => $nivelMonthly,
            'nivelYearly' => $nivelYearly,
            'nextLevel' => $nextVipLevel,
            'vipPoints' => $wallet->vip_points,
        ]);
    }
}
