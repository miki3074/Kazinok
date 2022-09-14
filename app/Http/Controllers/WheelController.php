<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Redis;
use App\Wheel;
use App\WheelBets;
use App\User;
use App\Setting;
use App\Profit;

class WheelController extends Controller
{
    public function get() {
        $game = Wheel::orderBy('id', 'desc')->first();
        if(!$game) Wheel::create(['hash' => bin2hex(random_bytes(16))]);

        $black = [];
        $yellow = [];
        $red = [];
        $green = [];

        $blackBank = 0;
        $yellowBank = 0;
        $redBank = 0;
        $greenBank = 0;

        $bets = WheelBets::where('wheel_bets.game_id', $game->id)
        ->select('wheel_bets.user_id', DB::raw('SUM(wheel_bets.price) as sum'), 'users.id', 'users.username', 'wheel_bets.color')
        ->join('users', 'users.id', '=', 'wheel_bets.user_id')
        ->groupBy('wheel_bets.user_id', 'wheel_bets.color')
        ->orderBy('sum', 'desc')
        ->get();
        $history = Wheel::select('winner_color')->where('status', 2)->orderBy('id', 'desc')->limit(25)->get();
        $list = [];
        foreach($history as $color) {
            $list[] = $color->winner_color;
        }
        foreach($bets as $bet) {
            if($bet->color == 'black') {
                $black[] = $bet;
                $blackBank += $bet->sum;
            } else if($bet->color == 'red') {
                $red[] = $bet;
                $redBank += $bet->sum;
            } else if($bet->color == 'green') {
                $green[] = $bet;
                $greenBank += $bet->sum;
            }
        }
        $players = [
            'black' => $black,
            'red' => $red,
            'green' => $green
        ];
        $bank = [
            'black' => $blackBank,
            'red' => $redBank,
            'green' => $greenBank
        ];
        return ['players' => $players, 'bank' => $bank, 'history' => $list];
    }

    public function betWheel($color, Request $r) {
      $game = Wheel::orderBy('id', 'desc')->first();
      if(!$game) Wheel::create(['hash' => bin2hex(random_bytes(16))]);

      $bet = preg_replace('/[^0-9.]/', '', $r->bet);

      $user = User::where('api_token', $r->token)->first() ?? false;
      if(!$user) return response()->json(['error' => 'true', 'message' => 'Пройдите авторизацию']);
      if($user->ban) return response()->json(['error' => 'true', 'message' => 'Ваш аккаунт заблокирован']);
      if($game->status == 1 || $game->status == 2) return response()->json(['error' => 'true', 'message' => 'Прием ставок закрыт']);
      if($color != 'black' && $color != 'red' && $color != 'green') return response()->json(['error' => 'true', 'message' => 'Ошибка! Попробуйте позже']);
      if($user->balance < $bet || !$user->balance) return response()->json(['error' => 'true', 'message' => 'Недостаточно монет']);
      if($bet < 1 || !$bet) return response()->json(['error' => 'true', 'message' => 'Ставки от 1 монеты']);
      if($this->tooFast()) return response()->json(['error' => 'true', 'message' => 'Не спешите']);

      $user->balance -= $bet;
      $user->save();
      
      $bet_client = number_format($bet, 2, '.', ' ');
      $this->redis = Redis::connection();
      $database = WheelBets::insertGetId(['user_id' => $user->id, 'color' => $color, 'price' => $bet, 'game_id' => $game->id]);
      $total = WheelBets::where('game_id', $game->id)->where('user_id', $user->id)->where('color', $color)->sum('price');

      $wheel_data = [
        'type'=> "add_wheel",
        'to' => $color,
        'id' => $database,
        'user_id' => $user->id,
        'betsum' => $bet_client,
        'total_bet' => $total,
        'username' => $user->username,
        'color' => [
          'black' => round(WheelBets::where('game_id', $game->id)->where('color', 'black')->sum('price'), 2),
          'red' => round(WheelBets::where('game_id', $game->id)->where('color', 'red')->sum('price'), 2),
          'green' => round(WheelBets::where('game_id', $game->id)->where('color', 'green')->sum('price'), 2),
        ],
        'players' => $this->get()['players']
      ];
      $this->redis->publish("wheel", json_encode($wheel_data));

      if($user->wager < 0 || $user->wager - $bet < 0) {
        $user->wager = 0;
        $user->save();
      } else {
          $user->decrement('wager', $bet);
      }

      return response()->json(['success' => 'true', 'balance' => $user->balance]);
    }
    public function getColor($color) {
      $list = [
        [1531, 'green', 13],
        [1538, 'black', 2],
        [1544, 'red', 2],
        [1550, 'black', 2],
        [1556.5, 'red', 2],
        [1562.5, 'black', 2],
        [1570.5, 'red', 2],
        [1577.5, 'black', 2],
        [1583.5, 'red', 2],
        [1591.5, 'black', 2],
        [1596.5, 'red', 2],
        [1601.5, 'black', 2],
        [1610.5, 'red', 2],
        [1617, 'green', 13],
        [1624, 'red', 2],
        [1632, 'black', 2],
        [1637, 'red', 2],
        [1643, 'black', 2],
        [1650, 'red', 2],
        [1658, 'black', 2],
        [1663, 'red', 2],
        [1671, 'black', 2],
        [1677, 'red', 2],
        [1684, 'black', 2],
        [1689, 'red', 2],
        [1697, 'black', 2],
        [1704, 'red', 2],
        [1710, 'black', 2],
        [1717, 'green', 13],
        [1724, 'black', 2],
        [1730, 'red', 2],
        [1735, 'black', 2],
        [1744, 'red', 2],
        [1751, 'black', 2],
        [1757, 'red', 2],
        [1763, 'black', 2],
        [1772, 'red', 2],
        [1777, 'black', 2],
        [1782.5, 'red', 2],
        [1790, 'black', 2],
        [1797, 'red', 2],
        [1804, 'green', 13],
        [1810, 'red', 2],
        [1815, 'black', 2],
        [1824, 'red', 2],
        [1830, 'black', 2],
        [1838, 'red', 2],
        [1844, 'black', 2],
        [1851, 'red', 2],
        [1855.5, 'black', 2],
        [1865, 'red', 2],
        [1869, 'black', 2],
        [1878, 'red', 2],
        [1884, 'black', 2],
    ];
    $filter = array_filter($list, function($var) use($color) {
    return ($var[1] == $color);
    });
    shuffle($filter);

    $с = $filter[mt_rand(0, count($filter)-1)];
    return $с;
    }

    private function getPrices() {
    $game = Wheel::orderBy('id', 'desc')->first();
    $query = WheelBets::where('game_id', $game->id)
  ->select(DB::raw('SUM(price) as value'), 'color')
  ->groupBy('color')
  ->get();

    $list = [];
    foreach($query as $l) {
    $list[$l->color] = $l->value;
    }
    return $list;
    }

    public function close()
    {
      $game = Wheel::orderBy('id', 'desc')->first();
      $profit = Profit::orderBy('id', 'desc')->first();
    
      $game->status = 1;
      $game->save();

      if(!$game->winner_color) {
        $wcolor = $this->generateColor();
      } else {
        $wcolor = $game->winner_color;
      }
        
      $rotate = $this->getColor($wcolor);
      $game->winner_color = $rotate[1];
      $game->save();
      return response()->json(['success' => 'true', 'rotate' => $rotate, 'gameid' => $game->id]);
    }
    public function generateColor()
    {
      $game = Wheel::orderBy('id', 'desc')->first();
      $profit = Profit::orderBy('id', 'desc')->first();

      $prices = $this->getPrices();
      $pricesList = [];
      $colors = ['black', 'red', 'green'];
      $summaryBet = 0;

      foreach($colors as $color) {
        $pricesList[] = [
          'color' => $color,
          'value' => ((isset($prices[$color])) ? $prices[$color] : 0)*(($color == 'black') ? 2 : (($color == 'yellow') ? 3 : (($color == 'red') ? 2 : 13)))
        ];
        $summaryBet += (isset($prices[$color])) ? $prices[$color] : 0;
      }

      usort($pricesList, function ($a, $b)
      {
          return ($a['value'] - $b['value']);
      });

      //$fits = [];

      foreach($pricesList as $a) {
        $fits[] = $a['color'];
      }
      //if(count($fits) >= 1) {
      $color = [];
      $chances = ['black' => 46.3, 'red' => 46.3, 'green' => 7.4];

      foreach($colors as $colors) {
        if(in_array($colors, $fits)) {
          for ($i = 0; $i < $chances[$colors]; $i++) $color[] = $colors;
        }
      }

      shuffle($color);
      $color = $color[mt_rand(0, count($color) - 1)];
      return $color;
      //} else {
      //  $min = reset($pricesList);
      //  return $min;
      //}
    }

    public function end() {
        $games = Wheel::orderBy('id', 'desc')->first();
        $games->status = 2;
        $games->save();
        $last_color = $games->winner_color;
        $this->sendWin($games->id, $last_color);
        $start = Wheel::where('status', '0')->count();
        if($start == 0) Wheel::create(['hash' => bin2hex(random_bytes(16))]);
        return response()->json(['success' => 'true', 'last' => $last_color]);
    }
    public function sendWin($game_id, $color) {
        $game = Wheel::orderBy('id', 'desc')->first();
        $profit = Profit::orderBy('id', 'desc')->first();

        $bets = WheelBets::select(DB::raw('SUM(price) as price'), 'user_id', 'balance')->where('game_id', $game_id)->where('color', $color)->groupBy('user_id', 'balance')->get();
        if($color == 'black')
        $multiplier = 2;
        if($color == 'red')
        $multiplier = 2;
        if($color == 'green')
        $multiplier = 13;

        $profit_collect = 0; // высчитываем профиты

        $betUsers = WheelBets::where('game_id', $game_id)->where('color', $color)->get();
        $loseUsers = WheelBets::where('game_id', $game_id)->where('color', '!=', $color)->get();
        foreach($betUsers as $b) { // выигрыши. Выситываем профит
            $user = User::where(['id' => $b->user_id])->first();
            $user->balance += $b->price*$multiplier;
            $user->wheel += $b->price*$multiplier - $b->price;
            $user->save();
            $b->win = 1;
            $b->win_sum = ($b->price*$multiplier)-$b->price;
            $b->save();
              
            if(!$user->is_youtuber) {
                $profit_collect -= $b->price*$multiplier-$b->price;
            }
        }
        foreach($loseUsers as $l) { // Проигрыши.
            $user = User::where(['id' => $l->user_id])->first();
            $user->wheel -= $l->price;
            $user->save();
            if(!$user->is_youtuber) {
                $profit_collect += $l->price;
            }
        }

        $profit->earn_wheel += $profit_collect;
        $profit->save();

        // Сохраняем итоговый профит, прибавляем либо отнимаем от итоговой суммы.
        //if($profit_collect <= 0) { // итоговый профит меньше 0, поэтому банк админа не трогаем. Там указывается только ЗАРАБОТОК админа
        //    $profit->bank_wheel += $profit_collect;
        //    $profit->save();
        //} else { // итоговый профит в плюс по отношению игры, поэтому банк админа меняем на +% к тому, который указан в админке
        //    $profit->bank_wheel += ($profit_collect / 100) * (100 - $profit->comission);
            
        //}

    }
}