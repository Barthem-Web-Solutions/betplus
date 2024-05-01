<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\AffiliateWithdraw;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $indications    = User::where('inviter', auth('api')->id())->count();
        $walletDefault  = Wallet::where('user_id', auth('api')->id())->first();

        return response()->json([
            'status'        => true,
            'code'          => auth('api')->user()->inviter_code,
            'url'           => config('app.url') . '/register?code='.auth('api')->user()->inviter_code,
            'indications'   => $indications,
            'wallet'        => $walletDefault
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function generateCode()
    {
        $code = $this->gencode();
        $setting = \Helper::getSetting();

        if(!empty($code)) {
            $user = auth('api')->user();
            \DB::table('model_has_roles')->updateOrInsert(
                [
                    'role_id' => 2,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id,
                ],
            );

            if(auth('api')->user()->update(['inviter_code' => $code, 'affiliate_revenue_share' => $setting->revshare_percentage])) {
                return response()->json(['status' => true, 'message' => trans('Successfully generated code')]);
            }

            return response()->json(['error' => ''], 400);
        }

        return response()->json(['error' => ''], 400);
    }

    /**
     * @return null
     */
    private function gencode() {
        $code = \Helper::generateCode(10);

        $checkCode = User::where('inviter_code', $code)->first();
        if(empty($checkCode)) {
            return $code;
        }

        return $this->gencode();
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function makeRequest(Request $request)
    {
        // Obtendo as configurações de saque do usuário
        $settings = Setting::where('id', 1)->first();

        // Verificando se as configurações foram encontradas e se os limites de saque foram definidos
        if ($settings) {
            $withdrawalLimit = $settings->withdrawal_limit;
            $withdrawalPeriod = $settings->withdrawal_period;
        } else {
            // Caso as configurações não tenham sido encontradas, defina os valores padrão ou trate conforme necessário
            $withdrawalLimit = null;
            $withdrawalPeriod = null;
        }

        if ($withdrawalLimit !== null && $withdrawalPeriod !== null) {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();

            switch ($withdrawalPeriod) {
                case 'daily':
                    break;

                case 'weekly':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
            }

            $withdrawalCount = AffiliateWithdraw::where('user_id', auth('api')->user()->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            if ($withdrawalCount >= $withdrawalLimit) {
                return response()->json(['message' => 'Você atingiu o limite de saques para este período'], 400);
            }
        }

        // \Log::info('PayLoss withdrawalCount '.$withdrawalCount);
        // \Log::info('PayLoss withdrawalLimit '.$withdrawalLimit);

        $rules = [
            'amount' => ['required', 'numeric', 'min:'.$settings->min_withdrawal, 'max:'.$settings->max_withdrawal],
            'pix_type' => 'required',
        ];

        switch ($request->pix_type) {
            case 'document':
                $rules['pix_key'] = 'required|cpf_ou_cnpj';
                break;
            case 'email':
                $rules['pix_key'] = 'required|email';
                break;
            default:
                $rules['pix_key'] = 'required';
                break;
        }


        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // Verificando se o usuário tem saldo suficiente para o saque
        $comission = auth('api')->user()->wallet->refer_rewards;

        if (floatval($comission) >= floatval($request->amount) && floatval($request->amount) > 0) {
            // Criando o registro de saque
            AffiliateWithdraw::create([
                'user_id'   => auth('api')->id(),
                'amount'    => $request->amount,
                'pix_key'   => $request->pix_key,
                'pix_type'  => $request->pix_type,
                'currency'  => 'BRL',
                'symbol'    => 'R$',
            ]);

            // Decrementando o saldo do usuário
            auth('api')->user()->wallet->decrement('refer_rewards', $request->amount);

            // Retornando mensagem de sucesso
            return response()->json(['message' => trans('Commission withdrawal successfully carried out')], 200);
        }

        // Retornando mensagem de erro se não houver saldo suficiente
        return response()->json(['message' => trans('Commission withdrawal error')], 400);
    }
}
