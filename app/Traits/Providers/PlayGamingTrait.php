<?php

namespace App\Traits\Providers;

use App\Enums\PlayGamingEnum;
use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\GamesKey;
use App\Models\Order;
use App\Models\Provider;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

trait PlayGamingTrait
{
    protected static $ENDPOINT = 'https://tbs2api.aslot.net/API/';
    protected static string $hall;
    protected static string $PGkey;
    protected static string $login;
    protected static int $isDemo = 0;

    /**
     * @return bool
     */
    public static function GetCredentialsPlayGaming(): bool
    {
        $gateway = GamesKey::first();

        self::$hall = $gateway->getAttributes()['play_gaming_hall'];
        self::$PGkey = $gateway->getAttributes()['play_gaming_key'];
        self::$login = $gateway->getAttributes()['play_gaming_login'];

        return true;
    }

    /**
     * Play Gaming Error
     *
     * @param $errorcode
     * @return string
     */
    private static function PlayGamingError($errorcode)
    {
        switch ($errorcode):
            case 'session_not_found':
                return 'Game session is not found';
            case 'session_closed':
                return 'Game session is closed';
            case 'writeBet:fail_bet':
                return 'Bet is greater than balance';
            case 'writeBet:fail_balance':
                return 'Fail balance response from site';
            case 'writeBet:mtwpu':
                return 'Hall MAXIMUM MINUS setting error';
            case 'writeBet:actionId_exist':
                return 'The action already exists';
            case 'writeBet:prePayment':
                return 'Prepayment error, insufficient credits';
            case 'writeBet:fail_response':
                return 'Fail response from the site';
            default:
                return '';
        endswitch;
    }

    /**
     * @param $gameId
     * @return mixed
     */
    public static function LaunchGamePlayGaming($gameId)
    {
        if (self::GetCredentialsPlayGaming()) {
            $data = [
                'cmd' => 'openGame',
                'gameId' => (string) $gameId,
                'hall' => self::$hall,
                'login' => auth('api')->user()->id .'#'. self::$login,
                'language' => 'pt_BR',
                'continent' => 'brl',
                'domain' => url('/'),
                'sessionId' => Session::getId(),
                'exitUrl' => url('/close'),
                // 'login' => auth('api')->user()->id,
                'demo' => '0',
                'jackpots' => '0',
            ];

            $signature = self::calculateSignature($data, self::$PGkey);
            $data['sign'] = $signature;

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(self::$ENDPOINT . 'openGame/', $data);

            if ($response->successful()) {
                $json = $response->json();
                return $json['content']['game']['url'];
            }
        }
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    private static function calculateSignature($data, $key)
    {
        ksort($data);
        $stringToSign = http_build_query($data) . $key;
        return hash('sha256', $stringToSign);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public static function WebhooksPlayGaming($request)
    {
        // $user = User::find($request->key);
        $login = explode('#', $request->login);
        $login = $login[0];

        $user = User::find($login);
        $wallet = Wallet::where('user_id', $user->id)->where('active', 1)->first();
        $balance = $wallet->total_balance;
        $req = $request->all();

        if ($req['cmd'] == 'getBalance') {
            return response()->json([
                'status' => 'success',
                'error' => '',
                'login' => $req['login'],
                'balance' => $balance,
            ]);
        }

        $bet = $req['bet'];

        $changeBonus = Helper::DiscountBalance($wallet, $bet);
        if($changeBonus != 'no_balance') {
            if ($req['cmd'] == 'writeBet') {
                if ($request->bet > 0 && $request->win == 0) {
                    $transactionCreated = self::CreateTransactionPlayGaming($user->id, time(), $request->tradeId, 'check', $changeBonus, $bet, $request->gameId);
                    Helper::generateGameHistory(
                        $wallet->user_id,
                        'bet',
                        0,
                        $request->bet,
                        $transactionCreated->type_money,
                        $transactionCreated->transaction_id
                    );

                } elseif ($request->win > 0) {
                    $transactionCreated = self::CreateTransactionPlayGaming($user->id, time(), $request->tradeId, 'check', $changeBonus, $bet, $request->gameId);
                    Helper::generateGameHistory(
                        $wallet->user_id,
                        'win',
                        $request->win,
                        0,
                        $transactionCreated->type_money,
                        $transactionCreated->transaction_id
                    );
                }

                return response()->json([
                    'status' => 'success',
                    'error' => '',
                    'login' => $req['login'],
                    'balance' => self::GetBalancePlayGaming($user->id),
                ]);
            }
        }else{
            return response()->json([
                'status' => 0,
                'error' => 'NO_BALANCE'
            ], 400);
        }

        return $request->all();
    }

    /**
     * Create Transactions
     *
     * @param $playerId
     * @param $betReferenceNum
     * @param $transactionID
     * @param $type
     * @param $changeBonus
     * @param $amount
     * @param $game
     * @return false
     */
    private static function CreateTransactionPlayGaming($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game)
    {
        $order = Order::create([
            'user_id' => $playerId,
            'session_id' => $betReferenceNum,
            'transaction_id' => $transactionID,
            'type' => $type,
            'type_money' => $changeBonus,
            'amount' => $amount,
            'providers' => 'playgaming',
            'game' => $game,
            'game_uuid' => $game,
            'round_id' => 1,
        ]);

        if ($order) {
            return $order;
        }

        return false;
    }

    /**
     * @param $userId
     * @return mixed
     */
    private static function GetBalancePlayGaming($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->where('active', 1)->first();
        return $wallet->total_balance;
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function GetGamesListPlayGaming()
    {
        if (self::GetCredentialsPlayGaming()) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post(self::$ENDPOINT, [
                'cmd' => 'gamesList',
                'hall' => self::$hall,
                'key' => self::$PGkey,
            ]);

            if ($response->successful()) {
                $json = $response->json();
                if ($json['status'] == "success") {
                    $gameList = $json['content']['gameList'];

                    foreach ($gameList as $game) {
                        if (!empty($game['img'])) {
                            $providerLabel = $game['label'];
                            $providerTitle = $game['title'];

                            $checkProvider = Provider::where('code', $providerLabel)->where('distribution', 'playgaming')->first();
                            if (empty($checkProvider)) {
                                $checkProvider = Provider::create([
                                    'code' => $providerLabel,
                                    'name' => $providerTitle,
                                    'rtp' => 80,
                                    'status' => 1,
                                    'distribution' => 'playgaming',
                                ]);
                            }

                            $checkGame = Game::where('game_id', $game['id'])->where('distribution', 'playgaming')->first();
                            if (empty($checkGame)) {
                                $image = self::uploadFromUrlPlayGaming($game['img'], $game['name']);
                                $data = [
                                    'provider_id' => $checkProvider->id,
                                    'game_id' => $game['id'],
                                    'game_code' => $game['id'],
                                    'game_name' => $game['name'],
                                    'technology' => 'html5',
                                    'distribution' => 'playgaming',
                                    'rtp' => 80,
                                    'cover' => $image,
                                    'status' => 1,
                                ];

                                Game::create($data);
                                echo "jogo salvo com sucesso \n";
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
    private static function uploadFromUrlPlayGaming($url, $name = null)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $fileContent = $response->getBody();

                $parsedUrl = parse_url($url);
                $pathInfo = pathinfo($parsedUrl['path']);
                $fileName = 'ever/' . ($name ?? $pathInfo['filename']) . '.' . ($pathInfo['extension'] ?? 'png');

                Storage::disk('public')->put($fileName, $fileContent);

                return $fileName;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
