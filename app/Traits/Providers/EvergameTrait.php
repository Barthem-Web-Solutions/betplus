<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\Provider;
use App\Models\GamesKey;
use App\Models\GGRGames;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\Missions\MissionTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait EvergameTrait
{
    use MissionTrait;

    /**
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
    public static function getCredentialsEvergame(): bool
    {
        $setting = GamesKey::first();

        self::$agentCode        = $setting->getAttributes()['evergame_agent_code'];
        self::$agentToken       = $setting->getAttributes()['evergame_agent_token'];
        self::$apiEndpoint      = $setting->getAttributes()['evergame_api_endpoint'];

        return true;
    }


    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $rtp
     * @param $provider
     * @return void
     */
    public static function UpdateRTPEvergame($rtp, $provider)
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "control_rtp",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "vendorCode" => $provider,
                "userCode"     => auth('api')->id() . '',
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
    public static function createUserEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "createUser",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "userCode"     => auth('api')->id() . '',
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
    public static function GameLaunchEvergame($provider_code, $game_code, $lang, $userId)
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "GetGameUrl",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "userCode"     => $userId.'',
                "vendorCode" => $provider_code,
                "gameCode"     => $game_code,
                "lang"          => $lang
            ];

            //\DB::table('debug')->insert(['text' => json_encode($postArray)]);
            $response = Http::post(self::$apiEndpoint, $postArray);

            $data = $response->json();
            $endpointwo = "https://api.777g.online/api/v1/game_launch";

            if($game_code === '98'|| $game_code === '68' || $game_code === '69' || $game_code === '126' || $game_code === '1543462' || $game_code === '1695365' ){
                $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();


                switch ($game_code) {
                    case '98':
                        $gamename = "fortune-ox";
                        break;
                    case '68':
                        $gamename = "fortune-mouse";
                        break;
                    case '69':
                        $gamename = "bikini-paradise";
                        break;
                    case '126':
                        $gamename = "fortune-tiger";
                        break;
                    case '1543462':
                        $gamename = "fortune-rabbit";
                        break;
                    case '1695365':
                        $gamename = "fortune-dragon";
                        break;
                }

                $postArray = [
                    "agentToken"   => '1a1c0e58-cdba-4bb1-b8d1-39fe53feecf2',
                    "secretKey"    => 'e92a238d-18a9-4931-a32a-447ef35b1842',
                    "user_code"     => $userId.'',
                    "provider_code" => $provider_code,
                    "game_code"     => $gamename,
                    "user_balance" => $wallet->total_balance,
                    "lang"          => $lang
                ];
                $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();


                $response = Http::post($endpointwo, $postArray);
                $data = $response->json();

                $data['launchUrl'] = $data['launch_url'];

            }

            if($data['status'] == 5) {
                if($data['msg'] == 'INVALID_USER') {
                    if(self::createUserEvergame()) {
                        return self::GameLaunchEvergame($provider_code, $game_code, $lang, $userId);
                    }
                }
            }else{

                if($provider_code == "Evolution_Casino"){
                    $data['launchUrl'] = $data['launchUrl']."&game_id=".$game_code;
                }

                return $data;
            }

        }

    }

    /**
     * Get FIvers Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return false|void
     */
    public static function getFiversUserDetailEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $dataArray = [
                "method"        => "call_players",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
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
     * Get FIvers Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $provider_code
     * @param $game_code
     * @param $lang
     * @param $userId
     * @return false|void
     */
    public static function getBalanceEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $dataArray = [
                "method"        => "money_info",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint, $dataArray);

            if($response->successful()) {
                $data = $response->json();

                return $data['agent']['balance'] ?? 0;
            }else{
                return false;
            }
        }

    }


    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function GetBalanceInfoEvergame($request)
    {
        $wallet = Wallet::where('user_id', $request->userCode)->where('active', 1)->first();
        if(!empty($wallet) && $wallet->total_balance > 0) {

            // \Log::info('Balance '.$wallet->total_balance);

            return response()->json([
                'balance' => $wallet->total_balance,
                'msg' => "SUCCESS"
            ]);
        }

        return response()->json([
            'balance' => 0,
            'msg' => "INSUFFICIENT_USER_FUNDS"
        ]);
    }

    /**
     * Set Transactions
     *
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function SetTransactionEvergame($request)
    {

        $data = $request->all();
        $wallet = Wallet::where('user_id', $data['userCode'])->where('active', 1)->first();

        if(!empty($wallet)) {
            if(isset($data['userCode'])) {
                $amount = floatval($data['amount']);

                if($amount < 0){
                    $bet = abs($amount);
                    $win = 0;
                }else{
                    $bet = 0;
                    $win = $amount;
                }

                if ($bet == 0 && $win == 0) {
                    return response()->json([
                        "status"      => 0,
                        "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
                        "msg"    => "SUCCESS",
                    ]);
                }

                $game = Game::where('game_id', $data['gameCode'])->first();
                $provider = Provider::where('id', $game->provider_id)->first();

                self::CheckMissionExist($data['userCode'], $game);

                $changeBonus = Helper::DiscountBalance($wallet, $bet);
                if($changeBonus != 'no_balance') {
                    return self::PrepareTransactionsEvergame($wallet, $data['userCode'], $data['txnCode'], $bet, $win, $game->game_name, $provider->code, $changeBonus, $data['txnType']);
                }else{
                    return false;
                }
            }
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
    private static function PrepareTransactionsEvergame($wallet, $userCode, $txnId, $betMoney, $winMoney, $gameCode, $providerCode, $changeBonus, $txnType)
    {
        $user = User::find($wallet->user_id);

        if($winMoney > $betMoney) {
            $transaction = self::CreateTransactionsEvergame($userCode, time(), $txnId, 'check', $changeBonus, $winMoney, $gameCode, $gameCode);

            if(!empty($transaction)) {

                /// salvar transação GGR
                GGRGames::create([
                    'user_id' => $userCode,
                    'provider' => $providerCode,
                    'game' => $gameCode,
                    'balance_bet' => $betMoney,
                    'balance_win' => $winMoney,
                    'currency' => $wallet->currency,
                    'aggregator' => "evergame",
                    "type" => "win"
                ]);


                /// pagar afiliado
                Helper::generateGameHistory($user->id, 'win', $winMoney, $betMoney, $changeBonus, $transaction->transaction_id);

                $wallet = Wallet::where('user_id', $wallet->user_id)->where('active', 1)->first();
                return response()->json([
                    "status"      => 0,
                    "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
                    "msg"    => "SUCCESS",
                ]);
            }
        }else{


            /// criar uma transação
            $checkTransaction = Order::where('transaction_id', $txnId)->first();
            if(empty($checkTransaction)) {
                $checkTransaction = self::CreateTransactionsEvergame($userCode, time(), $txnId, 'check', $changeBonus, $betMoney, $gameCode, $gameCode);
            }

            /// salvar transação GGR
            GGRGames::create([
                'user_id' => $userCode,
                'provider' => $providerCode,
                'game' => $gameCode,
                'balance_bet' => $betMoney,
                'balance_win' => $winMoney,
                'currency' => $wallet->currency,
                'aggregator' => "evergame",
                "type" => "loss"
            ]);

            /// pagar afiliado

            Helper::generateGameHistory($user->id, 'bet', $winMoney, $betMoney, $changeBonus, $txnId);
            $wallet = Wallet::where('user_id', $wallet->user_id)->where('active', 1)->first();

            return response()->json([
                "status"      => 0,
                "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
                "msg"    => "SUCCESS",
            ]);

        }
    }
    /**
     * @param $request
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function WebhooksEvergame($request)
    {
        switch ($request->method) {
            case "GetBalance":
                return self::GetBalanceInfoEvergame($request);
            case "ChangeBalance":
                return self::SetTransactionEvergame($request);
            default:
                return response()->json(['status' => 0]);
        }
    }


    /**
     * Create Transactions
     * Metodo para criar uma transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return false
     */
    private static function CreateTransactionsEvergame($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game, $pn)
    {

        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'Evergame',
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
     * Buscar Provedores
     * Metodo para Buscar Provedores
     *
     * @return bool
     */

    public static function getProviderEvergame()
    {
        if (self::getCredentialsEvergame()) {
            $response = Http::post(self::$apiEndpoint, [
                'method' => 'GetVendors',
                'agentCode' => self::$agentCode,
                'token' => self::$agentToken
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['vendors'])) { // Check if 'vendors' key exists in the response
                    foreach ($data['vendors'] as $vendor) { // Iterate through vendors
                        $cleanedName = explode('_', $vendor['vendorCode'])[0]; // Extract text before '_'
                        $checkProvider = Provider::where('code', $vendor['vendorCode'])->where('distribution', 'evergame')->first();
                        if (empty($checkProvider)) {
                            $dataProvider = [
                                'code' => $vendor['vendorCode'],
                                'name' => $cleanedName,
                                'rtp' => 80,
                                'status' => 1,
                                'distribution' => 'evergame',
                            ];
                            Provider::create($dataProvider);
                            echo "provedor criado com sucesso \n";
                        }
                    }
                }
                usleep(6000000); // Pausa de 1 segundo (1.000.000 microssegundos)
            }
        }
    }

    /**
     * Buscar Jogos
     * Metodo para Buscar Jogos
     *
     * @return bool
     */

    public static function getGamesEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $providers = Provider::where('distribution', 'evergame')->get();

            // Iterar sobre cada provedor individualmente
            foreach($providers as $provider) {
                $response = Http::post(self::$apiEndpoint, [
                    'method' => 'GetVendorGames',
                    'agentCode' => self::$agentCode,
                    'token' => self::$agentToken,
                    'vendorCode' => $provider['code']
                ]);

                if($response->successful()) {
                    $data = $response->json();

                    if(isset($data['vendorGames'])) {
                        foreach ($data['vendorGames'] as $game) {

                            // Extrai apenas os jogos com nome inglês
                            $gameName = str_replace('_', ' ', json_decode($game['gameName'], true)['en']);

                            // Verifica se o jogo já existe
                            $existingGame = Game::where('game_id', $game['gameCode'])->where('distribution', 'evergame')->first();

                            if(empty($existingGame)) {
                                if(!empty($game['imageUrl'])) {
                                    // Extracting image URL
                                    $imageUrl = json_decode($game['imageUrl'], true)['en'];
                                    $image = self::uploadFromUrlEverGame($imageUrl, $game['gameCode']);
                                } else {
                                    $image = null;
                                }

                                if(!empty($game['gameCode']) && !empty($gameName)) {
                                    $data = [
                                        'provider_id'   => $provider->id,
                                        'game_id'       => $game['gameCode'],
                                        'game_code'     => $game['gameCode'],
                                        'game_name'     => $gameName,
                                        'technology'    => 'html5',
                                        'distribution'  => 'evergame',
                                        'rtp'           => 80,
                                        'cover'         => $image,
                                        'status'        => 1,
                                    ];

                                    Game::create($data);
                                    echo "jogo salvo com sucesso \n";
                                }
                            }
                        }
                    }
                }

                // Pausa entre as solicitações para evitar sobrecarregar o servidor
                usleep(5000000); // Pausa de 1 segundo (1.000.000 microssegundos)
            }
        }
    }


    /**
     * @param $url
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function uploadFromUrlEverGame($url, $name = null)
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
                $fileName = 'ever/'.$fileName . '.' . $extension;

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
