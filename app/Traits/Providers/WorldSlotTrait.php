<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\GamesKey;
use App\Models\GGRGames;
use App\Models\Order;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\Missions\MissionTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait WorldSlotTrait
{
    use MissionTrait;

    /**
     * 456
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @var string
     */
    protected static $agentCode;
    protected static $agentToken;
    protected static $agentSecretKey;
    protected static $apiEndpoint;

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    public static function getCredentialsWorldSlot(): bool
    {
        $setting = GamesKey::first();

        self::$agentCode        = $setting->getAttributes()['worldslot_agent_code'];
        self::$agentToken       = $setting->getAttributes()['worldslot_agent_token'];
        self::$agentSecretKey   = $setting->getAttributes()['worldslot_agent_secret_key'];
        self::$apiEndpoint      = $setting->getAttributes()['worldslot_api_endpoint'];

        return true;
    }

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $rtp
     * @param $provider
     * @return void
     */
    public static function LoadingGamesWorldSlot()
    {
        if(self::getCredentialsWorldSlot()) {
            $postArray = [
                "agent_code" => "",
                "agent_token" => "",
                "user_code" => "test",
                "game_type" => "slot",
                "provider_code" => "PRAGMATIC",
                "game_code" => "vs20doghouse",
                "lang" => "en",
                "user_balance" => 1000
            ];

            $response = Http::post(self::$apiEndpoint.'game_launch', $postArray);

            if($response->successful()) {
                $games = $response->json();

                dd($games);
            }
        }
    }

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $rtp
     * @param $provider
     * @return void
     */
    public static function UpdateRTPWorldSlot($rtp, $provider)
    {
        if(self::getCredentialsWorldSlot()) {
            $postArray = [
                "method"        => "control_rtp",
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "provider_code" => $provider,
                "user_code"     => auth('api')->id() . '',
                "rtp"           => $rtp
            ];

            $response = Http::post(self::$apiEndpoint, $postArray);

            if($response->successful()) {

            }
        }
    }

    /**
     * Create User
     * Metodo para criar novo usuário
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return bool
     */
    public static function createUserWorldSlot()
    {
        if(self::getCredentialsWorldSlot()) {
            $postArray = [
                "method"        => "user_create",
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "user_code"     => auth('api')->id() . '',
            ];

            $response = Http::post(self::$apiEndpoint, $postArray);

            if($response->successful()) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Iniciar Jogo
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * Metodo responsavel para iniciar o jogo
     *
     */
    public static function GameLaunchWorldSlot($provider_code, $game_code, $lang, $userId)
    {
        if(self::getCredentialsWorldSlot()) {
            $wallet = Wallet::where('user_id', $userId)->first();

            $postArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "user_code"     => $userId.'',
                "provider_code" => $provider_code,
                "game_code"     => $game_code,
                'user_balance'  => $wallet->total_balance,
                'game_type'     => 'slot',
                "lang"          => $lang
            ];

            //\DB::table('debug')->insert(['text' => json_encode($postArray)]);
            $response = Http::post(self::$apiEndpoint.'/game_launch', $postArray);

            if($response->successful()) {
                $data = $response->json();

                if($data['status'] == 0) {
                    if($data['msg'] == 'Invalid User') {
                        if(self::createUserWorldSlot()) {
                            return self::GameLaunchWorldSlot($provider_code, $game_code, $lang, $userId);
                        }
                    }
                }else{
                    return $data;
                }
            }else{
                return false;
            }
        }

    }

    /**
     * Get WorldSlot Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return false|void
     */
    public static function getWorldSlotUserDetail()
    {
        if(self::getCredentialsWorldSlot()) {
            $dataArray = [
                "method"        => "call_players",
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint, $dataArray);

            if($response->successful()) {
                $data = $response->json();

                dd($data);
            }else{
                return false;
            }
        }

    }

    /**
     * Get WorldSlot Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $provider_code
     * @param $game_code
     * @param $lang
     * @param $userId
     * @return false|void
     */
    public static function getWorldSlotBalance()
    {
        try {
            if(self::getCredentialsWorldSlot()) {
                $dataArray = [
                    "agent_code"    => self::$agentCode,
                    "agent_token"   => self::$agentToken,
                ];

                $response = Http::post(self::$apiEndpoint.'/info', $dataArray);

                if($response->successful()) {
                    $data = $response->json();

                    return $data['agent_balance'] ?? 0;
                }else{
                    return 0;
                }
            }
        } catch (\Exception $e) {
            return 0;
        }

    }

    /**
     * Prepare Transaction
     * Metodo responsavel por preparar a transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @param $wallet
     * @param $userCode
     * @param $txnId
     * @param $betMoney
     * @param $winMoney
     * @param $gameCode
     * @return \Illuminate\Http\JsonResponse|void
     */
    private static function PrepareTransactionsWorldSlot($walletId, $userCode, $txnId, $betMoney, $winMoney, $gameCode, $providerCode)
    {
        $wallet = Wallet::find($walletId);
        $user = User::find($wallet->user_id);

        $typeAction  = 'bet';
        $bet = floatval($betMoney);

        $changeBonus = Helper::DiscountBalance($wallet, $bet);
        if($changeBonus != 'no_balance') {
            /// criar uma transação
            $transaction = self::CreateTransactionsWorldSlot($userCode, time(), $txnId, $typeAction, $changeBonus, $bet, $gameCode, $gameCode);
            if($transaction) {
                /// salvar transação GGR
                GGRGames::create([
                    'user_id' => $userCode,
                    'provider' => $providerCode,
                    'game' => $gameCode,
                    'balance_bet' => $bet,
                    'balance_win' => 0,
                    'currency' => $wallet->currency,
                    'aggregator' => "wordslot",
                    "type" => "loss"
                ]);

                return $transaction;
            }
        }else{
            return response()->json([
                'status' => 0,
                'msg' => "INVALID_USER"
            ]);
        }
        return false;
    }

    /**
     * @param $request
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function WebhooksWorldSlot($request)
    {
        switch ($request->method) {
            case "user_balance":
                return self::GetUserBalanceWorldSlot($request);
            case "game_callback":
                return self::GameCallbackWorldSlot($request);
            case "money_callback":
                return self::MoneyCallbackWorldSlot($request);
            default:
                return response()->json(['status' => 0]);
        }
    }

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function GetUserBalanceWorldSlot($request)
    {
        $wallet = Wallet::where('user_id', $request->user_code)->where('active', 1)->first();
        if(!empty($wallet) && $wallet->total_balance > 0) {
            return response()->json([
                'status' => 1,
                'user_balance' => $wallet->total_balance
            ]);
        }

        return response()->json([
            'status' => 0,
            'msg' => "INVALID_USER"
        ]);
    }

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse|void|null
     */
    private static function GameCallbackWorldSlot($request)
    {
        $data = $request->all();
        try {
            if($data['game_type'] == 'slot' && isset($data['slot'])) {
                return self::ProcessPlay($data, $request->user_code,'slot');
            }

            if($data['game_type'] == 'casino' && isset($data['casino'])) {
                return self::ProcessPlay($data, $request->user_code, 'casino');
            }

            return response()->json([
                'status' => 0,
                'msg' => 'INVALID_USER	'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $data
     * @param $userId
     * @param $type
     * @return \Illuminate\Http\JsonResponse|void
     */
    private static function ProcessPlay($data, $userId, $type)
    {
        $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
        if(!empty($wallet)) {
            $game = Game::where('game_code', $data[$type]['game_code'])->first();
            self::CheckMissionExist($userId, $game);

            /// verificar se é transação de vitoria duplicada
            $transactionWin = Order::where('transaction_id', $data[$type]['txn_id'])->where('type', 'win')->first();
            if(!empty($transactionWin)) {
                return response()->json([
                    'status' => 0,
                    'user_balance' => $wallet->total_balance,
                    'msg' => 'DUPLICATED_REQUEST'
                ]);
            }

            $transaction = Order::where('transaction_id', $data[$type]['txn_id'])->where('type', 'bet')->first();
            if(!empty($transaction)) {
                if(floatval($data[$type]['win']) > 0) {

                    GGRGames::create([
                        'user_id' => $wallet->user_id,
                        'provider' => $data[$type]['provider_code'],
                        'game' => $data[$type]['game_code'],
                        'balance_bet' => $data[$type]['bet'],
                        'balance_win' => $data[$type]['win'],
                        'currency' => $wallet->currency,
                        'aggregator' => "wordslot",
                        "type" => "win"
                    ]);

                    Helper::generateGameHistory(
                        $wallet->user_id,
                        'win',
                        $data[$type]['win'],
                        $transaction->amount,
                        $transaction->getAttributes()['type_money'],
                        $transaction->transaction_id
                    );

                    $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
                    return response()->json([
                        'status' => 1,
                        'user_balance' => $wallet->total_balance,
                    ]);
                }else{
                    return response()->json([
                        'status' => 0,
                        'user_balance' => $wallet->total_balance,
                        'msg' => 'DUPLICATED_REQUEST'
                    ]);
                }
            }


            /// verificar se tem saldo
            if(floatval($wallet->total_balance) >= $data[$type]['bet']) {

                /// verificar se usuário tem desafio ativo
                self::CheckMissionExist($userId, $game);
                $transaction = self::PrepareTransactionsWorldSlot(
                    $wallet->id, $userId,
                    $data[$type]['txn_id'],
                    $data[$type]['bet'],
                    $data[$type]['win'],
                    $data[$type]['game_code'],
                    $data[$type]['provider_code']);

                if($transaction) {
                    /// verificar se é transação de vitoria duplicada
                    $transactionWin2 = Order::where('transaction_id', $data[$type]['txn_id'])->where('type', 'win')->first();
                    if(!empty($transactionWin2)) {
                        $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
                        return response()->json([
                            'status' => 0,
                            'user_balance' => $wallet->total_balance,
                            'msg' => 'DUPLICATED_REQUEST'
                        ]);
                    }

                    $transaction = Order::where('transaction_id', $data[$type]['txn_id'])->where('type', 'bet')->first();
                    if(!empty($transaction)) {
                        if(floatval($data[$type]['win']) > 0) {
                            GGRGames::create([
                                'user_id' => $wallet->user_id,
                                'provider' => $data[$type]['provider_code'],
                                'game' => $data[$type]['game_code'],
                                'balance_bet' => $data[$type]['bet'],
                                'balance_win' => $data[$type]['win'],
                                'currency' => $wallet->currency,
                                'aggregator' => "wordslot",
                                "type" => "loss"
                            ]);

                            Helper::generateGameHistory(
                                $wallet->user_id,
                                'win',
                                $data[$type]['win'],
                                $transaction->amount,
                                $transaction->getAttributes()['type_money'],
                                $transaction->transaction_id
                            );

                            $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
                            return response()->json([
                                'status' => 1,
                                'user_balance' => $wallet->total_balance,
                            ]);
                        }
                    }

                    Helper::generateGameHistory(
                        $wallet->user_id,
                        'bet',
                        $data[$type]['win'],
                        $transaction->amount,
                        $transaction->getAttributes()['type_money'],
                        $transaction->transaction_id
                    );


                    $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
                    return response()->json([
                        'status' => 1,
                        'user_balance' => $wallet->total_balance,
                    ]);
                }else{
                    return response()->json([
                        'status' => 0,
                        'msg' => 'INSUFFICIENT_USER_FUNDS'
                    ]);
                }
            }
        }
    }

    /**
     * Money Callback World Slot
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function MoneyCallbackWorldSlot($request)
    {
        $data = $request->all();

        $transaction = Order::where('transaction_id', $data['txn_id'])->first();
        $wallet = Wallet::where('user_id', $transaction->user_id)->first();

        if(!empty($transaction) && !empty($wallet)) {

        }

        return response()->json([
            'status' => 1,
            'user_balance' => $wallet->total_balance
        ]);
    }


    /**
     * Create Transactions
     * Metodo para criar uma transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return false
     */
    private static function CreateTransactionsWorldSlot($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game, $pn)
    {

        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'worldslot',
            'game'          => $game,
            'game_uuid'     => $pn,
            'round_id'      => 1,
        ]);

        if($order) {
            return $order;
        }

        return false;
    }

    /**
     * Create User
     * Metodo para criar novo usuário
     *
     * @return bool
     */
    public static function getProviderWorldslot($param)
    {
        if(self::getCredentialsWorldSlot()) {
            $response = Http::post(self::$apiEndpoint.'provider_list', [
                'agent_code' => self::$agentCode,
                'agent_token' => self::$agentToken,
                'game_type' => $param, ///  [slot, casino, pachinko]
            ]);

            if($response->successful()) {
                $data = $response->json();
                if($data['status'] == 1) {
                    foreach ($data['providers'] as $provider) {
                        $checkProvider = Provider::where('code', $provider['code'])->where('distribution', 'worldslot')->first();
                        if(empty($checkProvider)) {

                            $dataProvider = [
                                'code' => $provider['code'],
                                'name' => $provider['name'],
                                'rtp' => 90,
                                'status' => 1,
                                'distribution' => 'worldslot',
                            ];

                            Provider::create($dataProvider);
                        }
                    }
                }
            }
        }
    }


    /**
     * Create User
     * Metodo para criar novo usuário
     *
     * @return bool
     */
    public static function getGamesWorldslot()
    {
        if(self::getCredentialsWorldSlot()) {
            $providers = Provider::where('distribution', 'worldslot')->get();
            foreach($providers as $provider) {
                $response = Http::post(self::$apiEndpoint.'/game_list', [
                    'agent_code' => self::$agentCode,
                    'agent_token' => self::$agentToken,
                    'provider_code' => $provider->code
                ]);

                if($response->successful()) {
                    $data = $response->json();

                    if(isset($data['games'])) {
                        foreach ($data['games'] as $game) {
                            $checkGame = Game::where('provider_id', $provider->id)->where('game_code', $game['game_code'])->first();
                            if(empty($checkGame)) {
                                if(!empty($game['banner'])) {
                                    $image = self::uploadFromUrlWorldSlot($game['banner'], $game['game_code']);
                                }else{
                                    $image = null;
                                }

                                if(!empty($game['game_code']) && !empty($game['game_name'])) {
                                    $data = [
                                        'provider_id'   => $provider->id,
                                        'game_id'       => $game['game_code'],
                                        'game_code'     => $game['game_code'],
                                        'game_name'     => $game['game_name'],
                                        'technology'    => 'html5',
                                        'distribution'  => 'worldslot',
                                        'rtp'           => 90,
                                        'cover'         => $image,
                                        'status'        => 1,
                                    ];

                                    Game::create($data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * @param $url
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function uploadFromUrlWorldSlot($url, $name = null)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $fileContent = $response->getBody();

                // Extrai o nome do arquivo e a extensão da URL
                $parsedUrl = parse_url($url);
                $pathInfo = pathinfo($parsedUrl['path']);
                //$fileName = $pathInfo['filename'] ?? 'file_' . time(); // Nome do arquivo
                $fileName  = $name ?? $pathInfo['filename'] ;
                $extension = $pathInfo['extension'] ?? 'png'; // Extensão do arquivo

                // Monta o nome do arquivo com o prefixo e a extensão
                $fileName = 'fivers/'.$fileName . '.' . $extension;

                // Salva o arquivo usando o nome extraído da URL
                Storage::disk('public')->put($fileName, $fileContent);

                return $fileName;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }





}


?>
