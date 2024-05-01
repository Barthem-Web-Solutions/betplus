<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\GamesKey;
use App\Models\Order;
use App\Models\Provider;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait VeniXTrait
{
    /**
     * @var string
     */
    public static $apiUri = 'https://sharkconnect.com.br/api/v1/';

    public static function VeniXAuthentication()
    {
        $gateway = GamesKey::first();
        if(!empty($gateway)) {
            $basicToken = base64_encode($gateway->getAttributes()['venix_agent_token'].':'.$gateway->getAttributes()['venix_agent_secret']);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer  '.$basicToken,
            ])->post(self::$apiUri.'auth/authentication');
            if($response->successful()) {
                $json = $response->json();

                return $json['access_token'];
            }
            return false;
        }
        return false;
    }

    /**
     * @return void
     */
    public static function getVenixProvider()
    {
        if($token = self::VeniXAuthentication()) {
            $response = Http::withToken($token)->get(self::$apiUri.'games/provider');
            if($response->successful()) {
                $json = $response->json();
                if($json['status']) {
                    foreach($json['providers'] as $provider) {

                        $data = [
                            'code'          => $provider['code'],
                            'name'          => $provider['name'],
                            'rtp'           => 80,
                            'status'        => 1,
                            'distribution'  => 'venix',
                        ];

                        $providerCheck = Provider::where('code', $provider['code'])->where('distribution', 'venix')->first();
                        if(empty($providerCheck)) {
                            Provider::create($data);
                            echo "provedor salvo com sucesso \n";
                        }
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getVenixGames()
    {
        if($token = self::VeniXAuthentication()) {
            $response = Http::withToken($token)->get(self::$apiUri.'games/all');
            if($response->successful()) {
                $json = $response->json();
                if($json['status']) {
                    foreach($json['games'] as $game) {
                        $provider = Provider::where('distribution', 'venix')->where('name', $game['provider_game'])->first();
                        $checkGame = Game::where('provider_id', $provider->id)->where('game_code', $game['game_code'])->first();
                        if(empty($checkGame)) {
                            $image = self::uploadFromUrlVeniX($game['banner'], $game['game_code']);
                            $data = [
                                'provider_id'   => $provider->id,
                                'game_id'       => $game['game_id'],
                                'game_code'     => $game['game_code'],
                                'game_name'     => $game['game_name'],
                                'technology'    => 'html5',
                                'distribution'  => 'venix',
                                'rtp'           => 90,
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
    }

    /**
     * @param Game $game
     * @return false|mixed
     */
    public function GameLaunchVeniX(Game $game)
    {
        if($token = self::VeniXAuthentication()) {
            $gateway = GamesKey::first();
            if(!empty($gateway)) {
                $request = \Illuminate\Support\Facades\Http::withToken($token)
                    ->withQueryParameters([
                        'agent_code' => $gateway->getAttributes()['venix_agent_code'],
                        'agent_token' => $gateway->getAttributes()['venix_agent_token'],
                        'game_id' => $game->game_id,
                        'type' => 'CHARGED',
                        'currency' => 'BRL',
                        'lang' => 'BR',
                        'user_id' => auth('api')->id(),
                    ])->get(self::$apiUri.'games/game_launch');

                if($request->successful()) {
                    $data = $request->json();
                    if(!empty($data['game_url'])) {
                        return $data['game_url'];
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function WebhookVeniX($request)
    {
        if (!empty($request->method)) {
            switch ($request->method) {
                case 'account_details':
                    return $this->AccountDetailsVeniX($request);
                case 'user_balance':
                    return $this->GetBalanceVeniX($request);
                case 'transaction':
                    return $this->SetTransactionVeniX($request);
                case 'refund':
                    return $this->SetRefundVeniX($request);
                default:
                    return response()->json(['error' => 'Método desconhecido'], 400);
            }
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function AccountDetailsVeniX($request)
    {
        $user = User::find($request->user_id);
        if(!empty($user)) {
            return response()->json([
                'email' => $user->email,
                'date' => $user->created_at,
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'INVALID_USER'
        ], 400);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function GetBalanceVeniX($request)
    {
        $walletResp = Wallet::where('user_id', $request->user_id)->first();
        if($walletResp) {
            return response()->json([
                'status' => 1,
                'balance' => $walletResp->total_balance,
            ]);
        }

        return response()->json([
            'status' => 0,
            'error' => 'INVALID_USER'
        ], 400);
    }

    private function SetTransactionVeniX($request)
    {
        \Log::info('START - '. $request);

        $user = User::with(['wallet'])->find($request->user_id);
        $wallet = Wallet::where('user_id', $request->user_id)->first();

        if(!empty($user) && !empty($wallet)) {
            $bet = $request->bet;
            $win = $request->win;

            if($request->type == 'win') {
                $checkTransaction = Order::where('type', 'bet')->where('transaction_id', $request->transaction_id_old)->first();
                // \Log::info('3 - '. $checkTransaction);

                if(!empty($checkTransaction)) {
                    $transactionCreated = self::CreateVeniXTransactions($request->user_id, time(), $checkTransaction->transaction_id, 'check', $checkTransaction->type_money, $win, $request->game);

                    if(!empty($transactionCreated)) {
                        if($win >= 0) {
                            $result_bet = 'win';
                            Helper::generateGameHistory(
                                $wallet->user_id,
                                $result_bet,
                                $win,
                                0,
                                $checkTransaction->type_money,
                                $checkTransaction->transaction_id
                            );
                        }
                    }
                }
            }else{
                $changeBonus = Helper::DiscountBalance($wallet, $bet);
                if($changeBonus != 'no_balance') {
                    if($request->type == 'bet') {
                        $checkTransaction = Order::where('type', 'bet')->where('transaction_id', $request->transaction_id)->first();

                        if(!empty($checkTransaction)) {
                            return response()->json([
                                'status' => 0,
                                'error' => 'DOUBLED_BET'
                            ], 400);
                        }else{
                            /// cria uma transação
                            $transactionCreated = self::CreateVeniXTransactions($request->user_id, time(), $request->transaction_id, 'check', $changeBonus, $bet, $request->game);

                            if(!empty($transactionCreated)) {
                                $result_bet = 'bet';
                                Helper::generateGameHistory(
                                    $wallet->user_id,
                                    $result_bet,
                                    0,
                                    $bet,
                                    $changeBonus,
                                    $request->transaction_id
                                );
                            }

                            $walletResp = Wallet::where('user_id', $request->user_id)->first();
                            return response()->json([
                                'status' => 1,
                                'balance' => $walletResp->total_balance,
                            ]);
                        }
                    }
                }else{
                    return response()->json([
                        'status' => 0,
                        'error' => 'NO_BALANCE'
                    ], 400);
                }
            }
        }
        return response()->json([
            'status' => 0,
            'error' => 'INVALID_USER'
        ], 400);
    }

    /**
     * Create Transactions
     * Metodo para criar uma transação
     *
     * @return false
     */
    private static function CreateVeniXTransactions($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game)
    {
        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'venix',
            'game'          => $game,
            'game_uuid'     => $game,
            'round_id'      => 1,
        ]);

        // \Log::info('Order: '. $order);

        if($order) {
            return $order;
        }

        return false;
    }

    private function SetRefundVeniX($request)
    {

        return response()->json([
            'status' => 1,
        ]);
    }


    /**
     * @param $url
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function uploadFromUrlVeniX($url, $name = null)
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
