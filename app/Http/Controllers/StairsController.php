<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Coin;
use App\Profit;
use App\Setting;
use App\Game;
use DB;
use App\Telegram;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class StairsController extends Controller
{

    public function __construct(Request $r) {
        parent::__construct();
    }

    public function resolve(Request $r)
    {
        $user = User::query()->find($r->id);

        if ($user->is_admin != 1 && $user->id != 20 && $user->id != 28279) {
           return ['success' => false, 'message' => 'Игра временно отключена'];
        }

        if ($r->bet < 1 or !is_numeric($r->bet)) {
            return ['success' => false, 'message' => 'Минимальная ставка 1 руб.'];
        }

        if ($r->bet > 1000000) {
            return ['success' => false, 'message' => 'Максимальная ставка 1000000 руб.'];
        }

        if ($r->bomb < 1) {
            return ['success' => false, 'message' => 'Минимальное кол-во камней: 1'];
        }

        if ($r->bomb > 7) {
            return ['success' => false, 'message' => 'Максимальное кол-во камней: 7'];
        }

        if ($user->balance < $r->bet) {
            return ['success' => false, 'message' => 'Недостаточно средств на балансе'];
        }

        if ($user->stairs_is_active) {
            return ['success' => false, 'message' => 'У Вас уже есть активная игра'];
        }

        if ($user->ban) {
            return ['success' => false, 'message' => 'Ваш аккаунт заблокирован'];
        }
        
        $generated = $this->generate($r->bomb);

        $game = [
            'bet' => $r->bet,
            'type' => -1,
            'cell_1' => $r->bomb,
            'cell_2' => 0,
            'cell_3' => -1,
            'cell_4' => json_encode($generated['grid']),
            'win' => -1,
            'status' => -1,
            'fair' => [
                'salt' => $generated['salt'],
                'string' => $generated['string'],
                'hash' => $generated['hash']
            ]
        ];

        $user->decrement('balance', $r->bet);

        $user->update([
            'stairs_game' => json_encode($game),
            'stairs_is_active' => 1
        ]);

        if($user->wager < 0 || $user->wager - $r->bet < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $r->bet);
        }
        
        return [
            'success' => true,
            'message' => 'Игра началась!',
            'hash' => $generated['hash'],
            'balance' => $user->balance
        ];
    }

    public function resolvePath(Request $r)
    {
        $user = User::query()->find($r->id);
        $row_cell_id = $r->bomb;

        if (!$user->stairs_is_active) {
            return ['success' => false, 'message' => 'У Вас нет активной игры'];
        }

        $game = json_decode($user->stairs_game, true);
 
        $win = true; // Угадал клетку
        $finish = false;

        $profit = Profit::query()->find(1);

        $grid = json_decode($game['cell_4'], true);

        $mul = $this->getStairsMultiplierTable()[(int) $game['cell_1']][(int) $game['cell_2'] + 1];

        $game['cell_2'] = $game['cell_2'] + 1;

        $rows = [
            1 => [0, 20],
            2 => [0, 19],
            3 => [1, 18],
            4 => [3, 17],
            5 => [3, 19],
            6 => [2, 17],
            7 => [3, 17],
            8 => [7, 20],
            9 => [8, 20],
            10 => [8, 19],
            11 => [1, 10],
            12 => [1, 9],
            13 => [1, 8]
        ];

        $history = [];
        $his = [];
        if (isset($game['history'])) {
            $his = $game['history'];
        }
        /*\Log::info($game['cell_2']);
        \Log::info(json_encode($grid[(int) $game['cell_2'] - 1]));*/
        if($grid[(int) $game['cell_2'] - 1][$row_cell_id] == 1) {   //словил камень
 
            $win = false;
            $row = $grid[(int) $game['cell_2'] - 1];

            $game['status'] = 0;

            $user->update([
                'stairs_game' => json_encode($game)
            ]);

            $bombs = $grid;

            $user->update([
                'stairs_is_active' => 0,
                'stairs' => $user->stairs - $game['bet']
            ]);

            $bd = $this->createGame($user, false);

            $id = $bd->id;
            $history = [
                'id' => $bd->id,
                'bet' => $bd->bet,
                'chance' => $bd->chance,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => $bd->win
            ];
            
            if(!$user->is_youtuber) {
                $profit->update([
                    'earn_stairs' => $profit->earn_stairs + $game['bet']
                ]);
            }
        } else {
            $row = $grid[(int) $game['cell_2'] - 1];
            $his[] = [
                'row' => $game['cell_2'] - 1,
                'cell' => $row_cell_id
            ];

            if ($game['cell_2'] == 13) {
                $finish = true;

                $user->update([
                    'stairs_is_active' => 0
                ]);

                $bd = $this->createGame($user, true);

                $winSum = $this->cashout($user, $mul);

                $id = $bd->id;
                $history = [
                    'id' => $bd->id,
                    'bet' => $bd->bet,
                    'chance' => $bd->chance,
                    'username' => substr($user->username, 0, -2) . '...',
                    'win' => $bd->win
                ];
            }
        }

        if(!$win) $game['status'] = 0;

        $game['multiplier'] = $mul;
        $game['history'] = $his;
        $game['cell_4'] = json_encode($grid);

        $user->update([
            'stairs_game' => json_encode($game)
        ]);

        $profit = round($game['bet'] * $mul, 2);

        if(!$finish) {
            return [
                'success' => true,
                'status' => $win ? 'continue' : 'lose',
                'games' => (int) $game['cell_2'],
                'grid' => $win ? 'Game is still in progress' : $grid,
                'row' => $row,
                'mul' => $mul,
                'profit' => $profit,
            ];
        } else {
            return [
                'success' => true,
                'status' => 'finish',
                'games' => (int) $game['cell_2'],
                'grid' => $grid,
                'row' => $row,
                'mul' => $mul,
                'profit' => $profit,
            ];
        } 
    }

    public function resolveTake(Request $r)
    {
        $user = User::query()->find($r->id);

        try {
           DB::beginTransaction();

           $affectedRow = \App\User::where([['stairs_is_active', '=', 1], ['id', $user->id]])->update([
               'stairs_is_active' => 0
           ]);

           if (!$affectedRow) {
               return ['success' => false, 'message' => 'У вас нет активной игры'];
           }

            if($this->tooFastNew($user->id)) return ['success' => false, 'message' => 'Не спешите'];

            $data =  json_decode($user->stairs_game, true);

            if ($data['cell_2'] == 0) {
                return ['success' => false, 'message' => 'Сделайте хотя бы одну ставку'];
            }

            $game = $this->createGame($user, true);        

            $mul = $this->getStairsMultiplierTable()[(int) $data['cell_1']][(int) $data['cell_2']];

            $winSum = $this->cashout($user, $mul);

           DB::commit();
       } catch (\Exception $e) {
           DB::rollback();
           throw new \Exception($e->getMessage());
       }

        return [
            'success' => true,
            'message' => "Успешно",
            'profit' => $winSum,
            'balance' => $user->balance,
            'history' => json_encode([
                'id' => $game->id,
                'bet' => $game->bet,
                'chance' => $game->chance,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => $game->win
            ])
        ];
    }

    public function resolveGet(Request $r) {
        $user = User::query()->find($r->id);

        if (!$user->stairs_is_active) {
            return [
                'success' => false
            ];
        } else {
            $game = json_decode($user->stairs_game, true);

            $mul = 1;

            if(isset($game['multiplier'])) {
                $mul = $game['multiplier'];
            }
            if(!isset($game['history'])) {
                $game['history'] = [];
            }

            return [
                'success' => true,
                'hash' => $game['fair']['hash'],
                'bet' => $game['bet'],
                'bomb' => $game['cell_1'],
                'game' => $game['history'],
                'profit' => round($mul * $game['bet'], 2)
            ];
        }
    }

    public function generate($bomb)
    {
        $salt = Str::random(24);

        $rows = [
            1 => 19,
            2 => 18,
            3 => 19,
            4 => 16,
            5 => 18,
            6 => 14,
            7 => 13,
            8 => 12,
            9 => 11,
            10 => 10,
            11 => 8,
            12 => 8,
            13 => 7
        ];

        $grid = [];

        // 0 - пусто; 1 - камень
        for($r = 1; $r <= 13; $r++) {
            $row = [];
            for ($i = 0; $i <= $rows[$r]; $i++)
                $row[$i] = 0;

            $left = $bomb;
            do {
                $rr = mt_rand(0, $rows[$r]);
                if ($row[$rr] == 0) {
                    $row[$rr] = 1;
                    $left--;
                }
            } while ($left > 0);
            array_push($grid, $row);
        }

        $string = implode("|", $grid[0]).'|'.$salt;
        $hash = hash('sha512', $string);

        return [
            'grid' => $grid,
            'salt' => $salt,
            'string' => $string,
            'bombs' => $grid,
            'hash' => $hash
        ];
    }

    public function cashout($user, $mul)
    {
        $profit = Profit::query()->find(1);
        $game = json_decode($user->stairs_game, true);

        $winSum = round($game['bet'] * $mul, 2);

        $user->increment('balance', $winSum);
        $user->update([
            'stairs_game' => null,
            'stairs' => $user->stairs + ($winSum - $game['bet'])
        ]);

        if(!$user->is_youtuber) {
           $profit->update([
               'earn_stairs' => $profit->earn_stairs - ($winSum - $game['bet']),
           ]);
        }
        return $winSum;
    }

    public function createGame($user, $win)
    {
        $game = json_decode($user->stairs_game, true);
        //$coef =  $this->getCoef($user, $win);

        $mul = $this->getStairsMultiplierTable()[(int) $game['cell_1']][(int) $game['cell_2']];

        if ($win) {
            $sum = round($game['bet'] * $mul,2);
        } else {
            $sum = 0;
        }

        $game = Game::query()->create([
            'user_id' => $user->id,
            'game' => 'stairs',
            'bet' => $game['bet'],
            'chance' => $mul,
            'win' => $sum,
            'mine' => json_encode($game)
        ]);

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => $game->game,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->win
        ]));

        return $game;
    }

    public static function getStairsMultiplierTable()
    {
        return [
        1 => [
            13 => 2.59,
            12 => 2.26,
            11 => 2.02,
            10 => 1.81,
            9 => 1.72,
            8 => 1.57,
            7 => 1.46,
            6 => 1.37,
            5 => 1.28,
            4 => 1.21,
            3 => 1.13,
            2 => 1.08,
            1 => 1.02
        ],
        2 => [
            13 => 7.58,
            12 => 5.68,
            11 => 4.41,
            10 => 3.53,
            9 => 3.16,
            8 => 2.64,
            7 => 2.23,
            6 => 1.97,
            5 => 1.71,
            4 => 1.52,
            3 => 1.35,
            2 => 1.20,
            1 => 1.08
        ],
        3 => [
            13 => 24.85,
            12 => 15.54,
            11 => 10.36,
            10 => 7.25,
            9 => 6.10,
            8 => 4.58,
            7 => 3.52,
            6 => 2.90,
            5 => 2.32,
            4 => 1.95,
            3 => 1.61,
            2 => 1.36,
            1 => 1.14
        ],
        4 => [
            13 => 90.62,
            12 => 47.31,
            11 => 26.29,
            10 => 15.77,
            9 => 12.45,
            8 => 8.30,
            7 => 5.74,
            6 => 4.39,
            5 => 3.22,
            4 => 2.54,
            3 => 1.95,
            2 => 1.53,
            1 => 1.21
        ],
        5 => [
            13 => 391.52,
            12 => 135.57,
            11 => 73.58,
            10 => 36.79,
            9 => 27.11,
            8 => 15.81,
            7 => 9.73,
            6 => 6.87,
            5 => 4.58,
            4 => 3.38,
            3 => 2.39,
            2 => 1.76,
            1 => 1.29
        ],
        6 => [
            13 => 2204.61,
            12 => 601.15,
            11 => 213.72,
            10 => 93.49,
            9 => 63.96,
            8 => 31.98,
            7 => 17.22,
            6 => 11.15,
            5 => 6.68,
            4 => 4.58,
            3 => 2.96,
            2 => 2.03,
            1 => 1.39
        ],
        7 => [
            13 => 23712.58,
            12 => 3464.07,
            11 => 780.91,
            10 => 234.27,
            9 => 156.91,
            8 => 69.55,
            7 => 32.10,
            6 => 18.88,
            5 => 10.07,
            4 => 6.36,
            3 => 3.74,
            2 => 2.37,
            1 => 1.49
        ]
    ];
    }
}
