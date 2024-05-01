<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $withdraws = Withdrawal::whereUserId(auth('api')->id())->paginate();
        return response()->json(['withdraws' => $withdraws], 200);
    }
}
