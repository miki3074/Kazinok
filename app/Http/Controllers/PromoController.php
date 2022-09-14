<?php

namespace App\Http\Controllers;

use App\User;
use App\Payment;
use App\Promocode;
use App\Telegram;
use App\PromocodeActivation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    const MIN_SUM = 0;

    public function __construct() 
    {
		parent::__construct();
    }

    public function getPromo(Request $r)
    {

        $user = User::query()->find($r->id);

        if (!$user->is_vk) {
            return [
                'success' => false,
                'message' => 'Привяжите аккаунт ВКонтакте'
            ];
        }

        $paymentSum = Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum');

        if ($paymentSum < $this->config->min_bonus_sum) {
            return [
                'success' => false,
                'message' => 'Недостаточно пополнений'
            ];
        }

        if ((Carbon::now()->timestamp - Carbon::parse($user->referral_use_promo)->timestamp) < 86400 && $user->referral_use_promo) {
            $oldTime = 86400 - (Carbon::now()->timestamp - Carbon::parse($user->referral_use_promo)->timestamp);

            $hour = floor($oldTime / 3600);
            $sec = $oldTime % 60;
            $min = floor(($oldTime / 60) % 60);

            return [
                'success' => false,
                'message' => 'До ежедневного бонуса: ' . $hour . ':' . $min . ':' . $sec
            ];
        }
        
        $data = [
            ['percent' => 999, 'min' => 50, 'max' => 400],
            ['percent' => 1, 'min' => 400, 'max' => 10000]
        ];
        
        
        $percent = mt_rand(1, 1000); // Получаем случайную цифру
        
        $current = 0;
        $min = 0;
        $max = 0;
        
        # Получаем нужное сообщение
        foreach($data as $value){
            # Прибавляем к текущему проценту неудачно прошедшие проверку
            $current += $value['percent'];
            
            # Если найдено значение
            if($current >= $percent){

                $min = $value['min'];
                $max = $value['max'];
                
                break;
            }
        }
        
        

        $sum = round(mt_rand($min, $max) / 100, 2);

        $user->update([
            'daily_bonus' => $user->daily_bonus + $sum,
            'balance' => $user->balance + $sum,
            'referral_use_promo' => Carbon::now(),
            'wager' => $user->wager + $sum * 15,
        ]);

        return [
            'success' => true,
            'message' => 'Вы получили ' . $sum . ' руб.',
            'sum' => $sum
        ];
    }

    public function setPromo(Request $r)
    {
        $user = User::query()->find($r->id);

        if(!$user->vk_id || !$user->is_vk) {
            return [
                'success' => false,
                'message' => 'Привяжите страницу вконтакте'
            ];
        }

        $vk_check = $this->groupIsMember($user->vk_id);

        if($vk_check == 0) return [
            'success' => false,
            'message' => 'Вы не состоите в группе'
        ];
        if($vk_check == NULL) return [
            'success' => false,
            'message' => 'Произошла ошибка'
        ];

        //$pcount = Payment::query()->where([['user_id', $user->id], ['status', 1], ['created_at', '>=', \Carbon\Carbon::today()->subDays(7)]])->count(); //кол-во депов пользователя
        //if($pcount < 1) return [
        //    'success' => false,
        //    'message' => 'Необходимо иметь 1 депозит за последние 7 дней'
        //];

        $code = $r->get('promocode');

        $promo = Promocode::query()->where('name', $code)->first();

        if (!$promo) {
            return [
                'success' => false,
                'message' => 'Промокод не найден'
            ];
        }

        $allUsed = PromocodeActivation::query()->where('promo_id', $promo->id)->count('id');

        if ($allUsed >= $promo->activation) {
            return [
                'success' => false,
                'message' => 'Промокод закончился'
            ];
        }

        if ($promo->type == 'deposit') {
            return [
                'success' => false,
                'message' => 'Данный промокод можно применить при пополнении'
            ];
        }

        if(User::where([['created_ip', $user->created_ip], ['ban', 0]])->count() >= 4 || User::where([['used_ip', $user->used_ip], ['ban', 0]])->count() >= 4) {
            return [
                'success' => false,
                'message' => 'У вас есть мультиаккаунты. Вы не можете активировать промокод'
            ];
        }

        $used = PromocodeActivation::query()->where([['promo_id', $promo->id], ['user_id', $user->id]])->first();

        if ($used) {
            return [
                'success' => false,
                'message' => 'Вы уже использовали этот код'
            ];
        }

        if(PromocodeActivation::query()->where([['created_at', '>=', \Carbon\Carbon::today()], ['user_id', $user->id], ['type', 'balance']])->count() >= 2) {
            return [
                'success' => false,
                'message' => 'За сутки можно активировать только 2 промокода такого типа'
            ];
        }

        $user->increment('balance', $promo->sum);
        $user->increment('promo_bal_sum', $promo->sum);
        $user->increment('wager', $promo->sum * $promo->wager);
        $promo->decrement('act', 1);

        PromocodeActivation::query()->create([
            'promo_id' => $promo->id,
            'user_id' => $user->id,
            'type' => 'balance'
        ]);

        return [
            'success' => true,
            'message' => 'Вы получили ' . $promo->sum . ' руб.',
            'sum' => $promo->sum
        ];
    }

    public function get(Request $r)
    {
        $user = $r->user();

        if ((Carbon::now()->timestamp - Carbon::parse($user->referral_use_promo)->timestamp) < 86400 && $user->referral_use_promo) {
            return [
                'success' => true,
                'timer' => Carbon::parse($user->referral_use_promo)->addDay()->timestamp
            ];
        }

        return [
            'success' => false
        ];
    }
    public function vk_bonus(Request $r) {
        $user = $r->user();

        if($user->bonus_use != 0) {
            return [
                'success' => false,
                'message' => 'Вы уже получали данный бонус'
            ];
        }
        
        if(!$user->vk_id || !$user->is_vk) {
            return [
                'success' => false,
                'message' => 'Привяжите страницу вконтакте'
            ];
        }

        $vk_check = $this->groupIsMember($user->vk_id);

        if($vk_check == 0) return [
            'success' => false,
            'message' => 'Вы не состоите в группе'
        ];
        if($vk_check == NULL) return [
            'success' => false,
            'message' => 'Произошла ошибка'
        ];

        $user->update([
            'balance' => $user->balance + $this->config->connect_bonus,
            'bonus_use' => 1
        ]);

        return [
            'success' => true,
            'message' => 'Успешно',
            'sum' => $this->config->connect_bonus
        ];
    }
    
    public function groupIsMember($id) {
        $user_id = $id;

        $group = $this->curl('https://api.vk.com/method/groups.isMember?group_id='.$this->config->vk_id.'&user_id='.$user_id.'&access_token='.$this->config->vk_token.'&v=5.131');

        if ($id == 732279230 || $id == 131854175) {
            Telegram::create(["message" => json_encode($group)]);
        }
        
        if(isset($group['error'])) {
            $res = NULL;
        } else {
            $res = $group['response'];
        }
        return $res;
    }
}
