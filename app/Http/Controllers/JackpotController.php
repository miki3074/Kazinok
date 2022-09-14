<?php namespace App\Http\Controllers;

use App\User;
use App\Rooms;
use App\Profit;
use App\Jackpot;
use App\JackpotBets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use Redis;

class JackpotController extends Controller {
	
    public function __construct(Request $r) {
		parent::__construct();
		$this->room = $r->room;
		$this->user = $this->auth;
		$this->settings = $this->config;
		$this->game = Jackpot::where('room', $this->room)->orderBy('game_id', 'desc')->first();
		if(!$this->game) Jackpot::create([
			'room' => $this->room,
			'game_id' => 1,
			'hash' => bin2hex(random_bytes(16))
		]);
    }
	
	public function getStatus(Request $r) {
		$room = Rooms::where('name', $r->room)->first();
		$game = Jackpot::where('room', $room->name)->orderBy('id', 'desc')->first();

		if($game->status != 3) {
			if(JackpotBets::all()->where('game_id', $game->id)->unique('user_id')->count() >= 2) {
				Redis::publish('jackpot.timer', json_encode([
					'room' => $room->name,
					'time' => $room->time,
					'game' => $game->game_id
				]));
			}
		}

		return [
			'success' => $game
		];
	}
	public function getRooms() {
		$room = Rooms::where('status', 0)->get();
		return $room;
	}
	
	public function jackpot() {
		return view('pages.jackpot');
	}
	
	public function newGame(Request $r) {
		$room = Rooms::where('name', $r->get('room'))->first();
		if(is_null($room)) return response()->json([
			'success' => false,
			'msg' => 'Не удалось найти комнату, в которой вы хотите сделать ставку!'
		]);
		
        $game = Jackpot::create([
			'room' => $room->name,
			'game_id' => $this->game->game_id+1,
			'hash' => bin2hex(random_bytes(16))
		]);
		
		return response()->json([
			'success' => true,
			'data' => [
				'game' => [
					'game_id' => $game->game_id,
					'id' => $game->id,
					'hash' => $game->hash,
				],
				'time' => $room->time
			]
		]);
	}

	public function parseJackpotGame($id) {
		$game = Jackpot::where('id', $id)->first();
		if(!$game) return null;

		$room = Rooms::where('name', $game->room)->first();
		
		$bets = JackpotBets::where('game_id', $game->id)->orderBy('id', 'asc')->get();

		$returnPrice = 0;
		$lastTicket = 0;
		$returnBets = [];
		$returnAmount = [];
		$returnUsers = [];
		
		foreach($bets as $bet) {
			$user = (isset($returnUsers[$bet->user_id])) ? $returnUsers[$bet->user_id] : User::where('id', $bet->user_id)->first();
			if($user) {
				$betSum = $bet->sum;
				$lastTicket++;
				$returnUsers[$user->id] = $user;
				$returnUsers[$user->id]->color = $bet->color;
				$returnBets[] = [
					'user' => [
						'id' => $user->id,
						'user_id' => $user->id,
						'username' => $user->username,
						'avatar' => $user->avatar
					],
					'bet' => [
						'amount' => $bet->sum,
						'color' => $bet->color,
						'balance' => $bet->balance,
						'from' => floor($lastTicket),
						'to' => floor($lastTicket+($betSum*100))
					],
				];

				if(!isset($returnAmount[$user->id])) $returnAmount[$user->id] = $betSum; else $returnAmount[$user->id] += $betSum;

				$lastTicket += $betSum*100;
				$returnPrice += $betSum;
			}
		}
		
		foreach($returnBets as $key => $bet) {
			$returnBets[$key]['bet']['chance'] = number_format(($returnAmount[$bet['user']['id']]/$returnPrice)*100, 2);
		}
		
		$returnChances = [];
		foreach($returnUsers as $key => $user) {
			$returnChances[] = [
				'game_id' => $game->id,
				'sum' => $returnAmount[$user->id],
				'user' => [
					'id' => $user->id,
					'username' => $user->username,
					'avatar' => $user->avatar
				],
				'color' => $user->color,
				'chance' => number_format(($returnAmount[$user->id]/$returnPrice)*100, 2)
			];
		}
		usort($returnChances, function($a, $b) {
			return $b['chance']-$a['chance']; 
		});
		$circleStart = 0;
		$circleAll = 0;
		foreach($returnChances as $key => $ch) {
			$userChance = $ch['chance']/100;
			$circleAll += $userChance;
			$returnChances[$key]['circle'] = [
				'color' => $ch['color'],
				'start' => $circleStart,
				'end' => $circleStart + (360*$userChance)
			];

			$circleStart = $returnChances[$key]['circle']['end'];
		}

		foreach($returnUsers as $key => $user) $returnUsers[$key]['circleValue'] = ($user['chance']/$circleAll)*100;
		return [
			'success' => true,
			'data' => [
				'id' => $game->id,
				'game_id' => $game->game_id,
				'hash' => $game->hash,
				'amount' => $returnPrice,
				'chances' => $returnChances,
				'bets' => array_reverse($returnBets),
				'time' => $room->time,
				'room' => $room->name,
				'min' => $room->min,
				'max' => $room->max
			]
		];
	}

	public function initRoom(Request $r) {
		$room = Rooms::where('name', $r->get('room'))->first();
		if(is_null($room)) return [
			'success' => false
		];

		$game = Jackpot::where('room', $room->name)->orderBy('id', 'desc')->first();
		if(is_null($game)) return [
			'success' => false
		];

		return $this->parseJackpotGame($game->id);
	}

	public function newBet(Request $r) {
		$bet = $r->bet;
		$room = Rooms::where('name', $r->get('room'))->first();

		if(!$this->user) return ['success' => false, 'msg' => 'Авторизуйтесь'];
		if(is_null($room)) return ['success' => false, 'msg' => 'Не удалось найти комнату, в которой вы хотите сделать ставку!'];

		$game = Jackpot::where('room', $room->name)->orderBy('id', 'desc')->first();
		if(is_null($game)) return ['success' => false, 'msg' => 'Не удалось найти игру в комнате '.$room->name];
		if($game->status > 1) return ['success' => false, 'msg' => 'Ставки в этой игре закрыты!'];
		if($bet < 1 || !is_numeric($bet)) return ['success' => false, 'msg' => 'Сумма ставки от 1'];

		if($this->user->balance < $bet) return ['success' => false, 'msg' => 'Недостаточно средств!'];
		if($this->user->ban) return ['success' => false, 'msg' => 'Ваш аккаунт заблокирован'];
		if($this->tooFast()) return ['success' => false, 'msg' => 'Не спешите'];
		
		$this->user->balance -= $bet;
		$this->user->save();

		$mybet = JackpotBets::where('game_id', $game->id)->where('user_id', $this->user->id)->first();
		JackpotBets::insert([
			'room' => $room->name,
			'game_id' => $game->id,
			'user_id' => $this->user->id,
			'sum' => $bet,
			'color' => ($mybet) ? $mybet->color : $this->getRandomColor(),
		]);

		$data = $this->parseJackpotGame($game->id);
		if($data['success'] && count($data['data']['chances']) >= 2 && $game->status < 1) {
			Jackpot::where('id', $game->id)->update([
				'status' => 1
			]);
			Redis::publish('jackpot.timer', json_encode([
				'room' => $room->name,
				'time' => $room->time,
				'game' => $game->id
			]));
		}
			
		Redis::publish('jackpot', json_encode([
			'type' => 'update',
			'room' => $room->name,
			'data' => $data
		]));

		if($this->user->wager < 0 || $this->user->wager - $bet < 0) {
			$this->user->wager = 0;
			$this->user->save();
		  } else {
			  $this->user->decrement('wager', $bet);
		  }

		return ['success' => true, 'msg' => 'Ваша ставка Вошла в игру!', 'balance' => $this->user->balance];
	}


	public function getSlider(Request $r) {
		$room = Rooms::where('name', $r->get('room'))->first();
		if(!$room) return ['success' => false, 'msg' => 'Не удалось найти комнату '.$r->get('room')];

		$game = Jackpot::where('room', $room->name)->orderBy('id', 'desc')->first();
		if(!$game) return ['success' => false, 'msg' => 'Не удалось найти игру в комнате '.$room->name];

		if($game->id != $r->get('game')) return ['success' => false, 'msg' => 'Найдена игра #'.$game->id.'. Не является #'.$r->get('game')];

		$data = $this->parseJackpotGame($game->id);
		if(!$data['success']) return ['success' => false, 'msg' => 'Неизвестная ошибка! Повторяем...', 'retry' => true];

		$data = $data['data'];
		
		$winnerBet = null;
		if(!$game->winner_id) {
			$winnerTicket = ($game->winner_ticket > 0) ? $game->winner_ticket : mt_rand(0, $data['bets'][0]['bet']['to']);
		} else {
			$winner2 = [];
			foreach($data['bets'] as $key => $d) {
				if($game->winner_id == $data['bets'][$key]['user']['user_id']) $winner2[] = $d['bet'];
			}
			$winner2 = $winner2[array_rand($winner2)];
			$winnerTicket = mt_rand($winner2['from'], $winner2['to']);
		}
		foreach($data['bets'] as $bet) if($bet['bet']['from'] <= $winnerTicket && $bet['bet']['to'] >= $winnerTicket) $winnerBet = $bet;
		if(is_null($winnerBet)) return [
			'success' => false,
			'msg' => 'Не удалось найти выигрышную ставку! Повторяем..',
			'retry' => true
		];

		DB::beginTransaction();
		try {
			DB::table('jackpot')->where('id', $game->id)->update([
				'winner_id' => $winnerBet['user']['user_id'],
				'winner_ticket' => $winnerTicket,
				'status' => 2
			]);
			
			$winner_money = $this->sendMoney($winnerBet['user']['user_id'], $game->id);

			DB::commit();
		} catch(Exception $e) {
			DB::rollback();
			return [
				'success' => false,
				'msg' => 'Неизвестная ошибка! Повторяем...',
				'retry' => true
			];
		}
		
		$rotate = [];
		foreach($data['chances'] as $key => $d) {
			if($winnerBet['user']['user_id'] == $data['chances'][$key]['user']['id']) $rotate = $d['circle'];
		}
		$cords = null;
		$center = $rotate['end']-$rotate['start'];
		if(floor($center) > 1) $cords = mt_rand(floor($rotate['start']), floor($rotate['end']));
		if(floor($center) < 1) $cords = $rotate['start'] + ($center/2);

		Redis::publish('jackpot', json_encode([
			'type' => 'slider',
			'room' => $room->name,
			'data' => [
				'cords' => 1440+$cords,
				'winner_id' => $winnerBet['user']['id'],
				'winner_name' => $winnerBet['user']['username'],
				'winner_avatar' => $winnerBet['user']['avatar'],
				'winner_balance' => $winner_money[0],
				'winner_bonus' => $winner_money[1],
				'ticket' => $winnerTicket
			]
		]));

		return [
			'success' => true
		];
	}
	
	private function sendMoney($user_id, $game_id) {
		$game = Jackpot::where('id', $game_id)->first();
		$bet = JackpotBets::where('game_id', $game->id)->where('user_id', $user_id)->first();
		$money_bets = JackpotBets::where('game_id', $game->id)->sum('sum');
		$w_bet_money = JackpotBets::where('game_id', $game->id)->where('user_id', $game->winner_id)->sum('sum');
		$comission = round(($money_bets - $w_bet_money)/100*\App\Profit::query()->first()->jackpot_comission, 2);
		$sum_money = round($w_bet_money + (($money_bets - $w_bet_money) - ($money_bets - $w_bet_money)/100*\App\Profit::query()->first()->jackpot_comission), 2);

		$user = User::where('id', $user_id)->first();
		if(!is_null($user)) {
				$user->balance += $sum_money;
				$user->save();
		}
		JackpotBets::where(['game_id' => $game->id, 'user_id' => $bet->user_id])->update([
			'win' => 1
		]);
		
        $game->status = 3;
		$game->save();
		
		return true;
	}
	private function getRandomColor() {
        $color = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
		return $color;
	}
}