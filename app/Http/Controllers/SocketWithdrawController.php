<?php

namespace App\Http\Controllers;

use App\User;
use App\Payment;
use App\Withdraw;
use App\Telegram;
use App\ReferralPayment;
use App\ReferralWithdraw;
use App\Wallets;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;

class SocketWithdrawController extends Controller
{
    const SYSTEMS = [
        //1 => [
        //    'title' => 'ЮMoney',
        //    'title1' => 'ЮMoney',
        //    'comission' => 2
        //],
        1 => [
            'title' => 'ЮMoney - FK',
            'title1' => 'ЮMoney',
            'comission' => 5 //4
        ],
        2 => [
            'title' => 'Payeer',
            'title1' => 'Payeer',
            'comission' => 0 
        ],
        //4 => [
        //    'title' => 'Qiwi - XMpay',
        //    'title1' => 'QIWI',
        //    'comission' => 2
        //],
        4 => [
            'title' => 'Qiwi - FK',
            'title1' => 'QIWI',
            'comission' => 5 //5%
        ],
        5 => [
            'title' => 'Beeline',
            'title1' => 'Beeline',
            'comission' => 0
        ],
        6 => [
            'title' => 'MegaFon',
            'title1' => 'MegaFon',
            'comission' => 0
        ],
        7 => [
            'title' => 'MTS',
            'title1' => 'MTS',
            'comission' => 0
        ],
        //9 => [
        //    'title' => 'Карты - XMpay',
        //    'title1' => 'Карты',
        //    'comission' => 1, //3%
        //    'plus' => 0
        //],
        9 => [
            'title' => 'Карты - GETPAY',
            'title1' => 'Карты',
            'comission' => 5, //5%
            'plus' => 0 
        ],
        10 => [
            'title' => 'MasterCard',
            'title1' => 'Карты',
            'comission' => 0, //2%
            'plus' => 50
        ],
        11 => [
            'title' => 'Tele2',
            'title1' => 'Tele2',
            'comission' => 0
        ],
        12 => [
            'title' => 'FKWallet',
            'title1' => 'FKWallet',
            'comission' => 3
        ],
        14 => [
            'title' => 'Piastrix',
            'title1' => 'Piastrix',
            'comission' => 3
        ],
        15 => [
            'title' => 'Qiwi - RUBpay',
            'title1' => 'QIWI',
            'comission' => 4
        ],
        16 => [
            'title' => 'Карты - RUBpay',
            'title1' => 'Карты',
            'comission' => 3, //3%
            'plus' => 50
        ],
        17 => [
            'title' => 'Qiwi - XMpay',
            'title1' => 'Qiwi',
            'comission' => 2, //3%
        ],
        18 => [
            'title' => 'FKWallet - XMpay',
            'title1' => 'FKWallet',
            'comission' => 3, //3%
        ],
        19 => [
            'title' => 'Карты - XMpay',
            'title1' => 'Карты',
            'comission' => 3, //3%
            'plus' => 50
        ],
        20 => [
            'title' => 'QIWI - GetPay',
            'title1' => 'QIWI',
            'comission' => 5, //5%
        ],
        21 => [
            'title' => 'Piastrix',
            'title1' => 'Piastrix',
            'comission' => 3, //5%
        ],

    ];

    private $icons = [
        1 => 'yoomoney.png',
        2 => 'payeericon.png',
        4 => 'qiwi.svg',
        5 => 'beelineicon.png',
        6 => 'megafonicon.png',
        7 => 'mtsicon.png',
        9 => 'visamc.png',
        10 => 'mcicon.png',
        11 => 'tele2.png',
        12 => 'fkwallet.png',
        14 => 'piastrix.png',
        15 => 'qiwi.svg',
        16 => 'visamc.png',
        17 => 'qiwi.svg',
        18 => 'fkwallet.png',
        19 => 'visamc.png',
        20 => 'qiwi.svg',
        21 => 'piastrix.png',
    ];

    private $month = [
        'января',
        'февраля',
        'марта',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря'
    ];

    public function result(Request $r)
    {
        $order_id = $r->order_id;
        $user_order_id = $r->user_order_id;
        $status = $r->status;
        $amount = $r->amount;

        $sign = md5($this->config->wallet_id.$order_id.$user_order_id.$status.$amount.$this->config->wallet_secret);
        if($sign != $r->sign) return 'Bad sign!';

        if($status == 1) { // успешно выполнен
            Withdraw::query()->where('payment_id', $order_id)->update([
                'status' => 1
            ]);
        }

        if($status == 9) { // ошибка
            $withdraw = Withdraw::query()->where('payment_id', $order_id);

            $withdraw->where('payment_id', $order_id)->update([
                'status' => 2
            ]);

            User::where('id', $withdraw->first()->user_id)->update([
                'balance' => DB::raw('balance + ' . $withdraw->sumNoCom)
            ]);
        }

        return 'YES';
    }

    public function create(Request $r)
    {
        if ($this->config->withdraws_on == 1 || $this->config->fkwallet_only == 1) {

            $user = User::query()->find($r->id);

            $sum = $r->get('sum');
            $wallet = strip_tags($r->get('wallet'));
            $system = $r->get('system');

            if($this->tooFastNew($user->id)) return ['success' => false, 'message' => 'Не спешите'];

            //if ($this->config->xmpay_off == 1 && $system == 4 && $user->is_admin == 0) {
            //    return [
            //        'success' => false,
            //        'message' => 'Данный метод временно отключен'
            //    ];
            //}

            //if ($this->config->xmpay_off == 1 && $system == 9 && $user->is_admin == 0) {
            //    return [
            //        'success' => false,
            //        'message' => 'Данный метод временно отключен'
            //    ];
            //}
 
            //if ($system == 1) {
            //    return [
            //        'success' => false,
            //        'message' => 'Данный метод временно отключен'
            //    ];
            //}

            if ($system == 9) {
                if (Withdraw::query()->where([['user_id', $r->id], ['system', 9], ['status', 1], ['created_at', '>=', \Carbon\Carbon::today()->subDays(30)]])->orWhere([['user_id', $r->id], ['system', 9], ['status', 3], ['created_at', '>=', \Carbon\Carbon::today()->subDays(30)]])->sum('sumNoCom') + $sum > 20000) {
                    return [
                        'success' => false,
                        'message' => 'Выводить на карту можно не более 20000 в месяц. Попробуйте сумму меньше'
                    ];
                }
            }
            

            if ($sum > 5000 && $system == 9) {
                return [
                    'success' => false,
                    'message' => 'Максимальный вывод через данный метод 5000 рублей'
                ];
            }

            if ($sum > 1000 && $system == 4) {
                return [
                    'success' => false,
                    'message' => 'Максимальный вывод через данный метод 1000 рублей'
                ];
            }

            if ($this->config->fkwallet_only == 1 && $system != 12 && $system != 21) {
                return [
                    'success' => false,
                    'message' => 'Данный метод временно отключен'
                ];
            }

            //if ($system == 12 && $r->user()->is_admin != 1) {
            //    return [
            //        'success' => false,
            //        'message' => 'Данный метод временно отключен'
            //    ];
            //}

            if (($user->balance - $sum) < 0 ) {
                return [
                    'success' => false,
                    'message' => 'Недостаточно средств на балансе'
                ];
            }

            if ($sum < $this->config->min_withdraw_sum || !is_numeric($sum)) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма вывода: ' . $this->config->min_withdraw_sum . ' руб'
                ];
            }

            if ($system == 20 && $sum > 5000 ) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода для этого метода: 5000 руб'
                ];
            }

            if ($system == 21 && $sum > 10000 ) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода для этого метода: 10000 руб'
                ];
            }

            if ($system == 15 && $sum < 1000 || $system == 16 && $sum < 1000 ) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма вывода для этого метода: 1000 руб'
                ];
            }

            if ($system == 9 && $sum < 1000 ) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма вывода для этого метода: 1000 руб'
                ];
            }

            if ($system == 15 && $sum > 10000 || $system == 16 && $sum > 10000 ) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода для этого метода: 10000 руб'
                ];
            }

            if ($system == 15 && $sum > 5000) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода для этого метода: 5000 руб'
                ];
            }

            if ($sum > $this->config->max_qyc_withdraw_sum && $system == 4) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода на QIWI: ' . $this->config->max_qyc_withdraw_sum . ' руб'
                ];
            }

            if ($sum > $this->config->max_qyc_withdraw_sum && $system == 1) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода на ЮMoney: ' . $this->config->max_qyc_withdraw_sum . ' руб'
                ];
            }

            if (($sum > $this->config->max_qyc_withdraw_sum && $system == 9) || ($sum > $this->config->max_qyc_withdraw_sum && $system == 10)) {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма вывода на карты: ' . $this->config->max_qyc_withdraw_sum . ' руб'
                ];
            }
            if ($this->config->max_withdraw_sum !== null) {
                if (($sum > $this->config->max_withdraw_sum)) {
                    return [
                        'success' => false,
                        'message' => 'Максимальная сумма вывода на данный метод: ' . $this->config->max_withdraw_sum . ' руб'
                    ];
                }
            }        

            if (!isset(self::SYSTEMS[$system])) {
                return [
                    'success' => false,
                    'message' => 'Выбранная платежная система не найдена'
                ];
            }

            if ($sum > $user->balance) {
                return [
                    'success' => false,
                    'message' => 'Недостаточно средств на балансе'
                ];
            }

            if($user->ban) {
                return [
                    'success' => false,
                    'message' => 'Ваш аккаунт заблокирован'
                ];
            }

            if(strlen($wallet) < 5 || strlen($wallet) > 25) {
                return [
                    'success' => false,
                    'message' => 'Введите корректный кошелек'
                ];
            }
            
            if ($system >= 4 && $system <= 7 || $system == 11 || $system == 15 || $system == 20) {
                if (strlen($wallet) < 8 || strlen($wallet) > 20 || !is_numeric($wallet)) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            } else if ($system === 2) {
                if (substr($wallet, 0, 1) != "P") {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }

                if (!preg_match("/^[0-9]{7,11}$/", substr($wallet, 1))) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            } else if($system == 12) {
                if (substr($wallet, 0, 1) != "F") {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }

                if (!preg_match("/^[0-9]{7,11}$/", substr($wallet, 1))) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                } 
            } else if ($system === 1) {
                if (!preg_match("/41001\d{9,10}/", $wallet)) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            } else if ($system === 22 || $system === 16) {
                if (!preg_match("/^(5[1-5]|677189)|^(222[1-9]|2[3-6]\d{2}|27[0-1]\d|2720)/", $wallet) && !preg_match("/^4/", $wallet)) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            } else if ($system === 10) {
                if (!preg_match("/^(?:5[1-5][0-9]{14})$/", $wallet) || !preg_match("/^(?:4[0-9]{12}(?:[0-9]{3})?)$/", $wallet)) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            } else if ($system === 11) {
                if (!filter_var($wallet, FILTER_VALIDATE_EMAIL)) {
                    return [
                        'success' => false,
                        'message' => 'Введите корректный кошелек'
                    ];
                }
            }


            if(Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum') < $this->config->min_dep_withdraw) return [
                'success' => false,
                'message' => 'Необходимо пополнить баланс на: ' . $this->config->min_dep_withdraw . ' руб'
            ];

            $pcount = Payment::query()->where([['user_id', $user->id], ['status', 1]])->count(); //кол-во депов пользователя
            $psum = Payment::query()->where([['created_at', '>=', \Carbon\Carbon::today()->subDays($this->config->deposit_per_n)], ['user_id', $user->id], ['status', 1]])->sum('sum');

            if($pcount >= 0 && $psum < $this->config->deposit_sum_n) return [
                'success' => false,
                'message' => 'Необходимо пополнить баланс на: ' . $this->config->deposit_sum_n . ' руб за последние ' . $this->config->deposit_per_n . ' дней'
            ];

            if(Withdraw::where('user_id', $user->id)->where('status', 0)->count() >= $this->config->withdraw_request_limit) return [
                'success' => false,
                'message' => 'Дождитесь предыдущих выводов'
            ];

            if($user->wager > 0 && $user->id != 20) return [
                'success' => false,
                'message' => 'Вам необходимо отыграть еще ' . $user->wager . ' руб'
            ];

            /*if ($system == 14 || $system == 12) {
                $qiwiwdw = Withdraw::query()->where([['user_id', $user->id], ['system', 20]])->count();
                $cardwdw = Withdraw::query()->where([['user_id', $user->id], ['system', 9]])->count();
                if($qiwiwdw < 1 && $cardwdw < 1) {
                    return [
                        'success' => false,
                        'message' => 'Чтобы выводить на данный метод, необходимо иметь успешный вывод на QIWI или карту'
                    ];
                }
            }*/
            
            $sumNoCom = $sum;
            $minus = isset(self::SYSTEMS[$system]['plus']) ? self::SYSTEMS[$system]['plus'] : 0;
            //$minus = 0;
            $sumWithCom = $sum - ( $sum / 100 * self::SYSTEMS[$system]['comission'] ) - $minus;

            $wallets = Wallets::query()->where([['user_id', $user->id], ['wallet', 'like', '%'.$wallet.'%']])->first();

            if ($user->id != 6) {
                if ($wallets == null) {
                    Wallets::query()->create([
                        'user_id' => $user->id,
                        'wallet' => $wallet,
                        'system' => $system
                    ]);
                }
            }         

            $wdrw = new Withdraw();
            $wdrw->user_id = $user->id;
            $wdrw->username = $user->username;
            $wdrw->sum = $sumWithCom;
            $wdrw->sumNoCom = $sumNoCom;
            $wdrw->wallet = $wallet;
            $wdrw->system = $system;
            $wdrw->save();

            $user->decrement('balance', $sumNoCom); //auto

            //$user = $user;
            $wdrw_id = $wdrw->id;
            $this->tryAuto($wdrw_id);

        //    $data = [
      //          'wallet' => substr($wallet, 0, -5) . '...',
       //         'method' => self::SYSTEMS[$system]['title1'],
         //       'username' => substr($user->username, 0, -2) . '...',
         //       'sum' => $sumNoCom,                
       //         'date' => $wdrw->created_at->format('H:i:s')
    //        ];
            //Redis::publish('newWithdraw', json_encode($data));

            return [
                'success' => true,
                'withdraws' => $this->getWithdrawsInUser($user->id)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Выводы временно отключены'
            ];
        }
    }

     public function tryAuto($wdrw_id)
    {
        $withdraw = Withdraw::query()->find($wdrw_id);
        $user = User::query()->find($withdraw->user_id);
        
        $system = 0;

        $status = 1;

        switch($withdraw->system) {
            case 1:
                $system = "yoomoney";
            break;
            case 2:
                $system = "payeer";
            break;
            case 4:
                $system = "qiwi";
            break;
            case 5:
                $system = "mobile";
            break;
            case 6:
                $system = "mobile";
            break;
            case 7:
                $system = "mobile";
            break;
            case 9:
                $system = "cardgp";
            break;
            case 10:
                $system = "card";
            break;
            case 11:
                $system = "mobile";
            break;
            case 12:
                $system = "fkwallet";
            break;
            case 14:
                $system = "piastrix";
            break;
            case 15:
                $system = "qiwi1";
            break;
            case 16:
                $system = "card1";
            break;
            case 17:
                $system = "qiwi2";
            break;
            case 18:
                $system = "fkwallet2";
            break;
            case 19:
                $system = "card2";
            break;
            case 20:
                $system = "qiwiGP";
            break;
            case 21:
                $system = "piastrix1";
            break;
            default:
                return redirect()->back()->with('error', 'Платежная система не найдена');
            break;
        }

        if(User::query()->find($withdraw->user_id)->is_youtuber) {
            $withdraw->update([
                'status' => $status,
                'is_auto' => 1,
                'fake' => 1
            ]);
            return [
                'success' => true,
                'withdraws' => $this->getWithdrawsInUser($user->id)
            ];
        }

        $auto = true;
        $reason = [];
        $info = [];

        if ($user->comment) {
    		$info[] = "Комментарий: ". $user->comment;
    	} 

        if($withdraw->system == 17 || $withdraw->system == 18 || $withdraw->system == 19) {
            $auto = false;
        }

        if ($withdraw->sumNoCom <= 1000) {
            
        } else {
            $auto = false;
            $reason[] = "Cумма > 1000";
        }

        $refi = User::query()->where("referral_use", $user->id)->first();
        $refpayment = 0;
        $refwithdraw = 0;
        $ref_bonus = \App\Setting::query()->find(1)->ref_bonus;
        if ($refi) {
            $refpayment = ReferralPayment::query()->where('referral_id', $user->id)->sum('sum');
            $refwithdraw = ReferralWithdraw::query()->where('referral_id', $user->id)->sum('sum');
            if($refpayment > 0) {
                if (round($refpayment * (\App\Setting::query()->find(1)->ref_perc / 100), 2) < round($refpayment - $refwithdraw, 2) ) {
                } else {
                    $auto = false;
                    $reason[] = "10% > прибыли";
                }
            }
            if($refpayment > 0) {
                if (round(($user->ref_bonus_cnt * $ref_bonus), 2) < round($refpayment - $refwithdraw, 2) ) {
                } else {
                    $auto = false;
                    $reason[] = "15р > прибыли";
                }
            }
            if($refpayment > 0) {
                if (round($refpayment * (\App\Setting::query()->find(1)->ref_perc / 100) + ($user->ref_bonus_cnt * $ref_bonus), 2) < round($refpayment - $refwithdraw, 2) ) {
                } else {
                    $auto = false;
                    $reason[] = "Рефка > прибыли";
                }
            }

            if($refpayment > 0 && $user->ref_bonus_cnt > 7) {
                if (round($refpayment * (\App\Setting::query()->find(1)->ref_perc / 100) + ($user->ref_bonus_cnt * $ref_bonus), 2) < round($refpayment - $refwithdraw - ($user->ref_bonus_cnt * $ref_bonus), 2) ) {
                } else {
                    $auto = false;
                    $reason[] = ">0 депов, >7 рефов";
                }
            } else if($refpayment <= 0 && $user->ref_bonus_cnt > 7) {
                $auto = false;
                if (round($refpayment * (\App\Setting::query()->find(1)->ref_perc / 100) + ($user->ref_bonus_cnt * $ref_bonus), 2) < round($refpayment - $refwithdraw - ($user->ref_bonus_cnt * $ref_bonus), 2) ) {
                } else {
                    $auto = false;
                    $reason[] = "0 депов рефов, >7 рефов";
                }
            }

        }

        $pribb = 0;
        //$wdws = round(Withdraw::query()->where([['user_id', $user->id], ['status', 1], ['created_at', '>=', date('2022-05-14').' 00:00:00']])->orWhere([['user_id', $user->id], ['status', 3], ['created_at', '>=', date('2022-05-14').' 00:00:00']])->sum('sumNoCom') + $withdraw->sumNoCom, 2);
        //$pays = round(Payment::query()->where([['user_id', $user->id], ['status', 1], ['created_at', '>=', date('2022-05-14').' 00:00:00']])->sum('sum'), 2);
        $wdws = round(Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->orWhere([['user_id', $user->id], ['status', 3]])->sum('sumNoCom') + $withdraw->sumNoCom, 2);
        $pays = round(Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2);
        $razn = $pays - $wdws;
        $info[] = "Прибыль: " . $razn;
        $info[] = "Депы: " . $pays;
        $info[] = "Выводы: " . $wdws;


        $refdep1 = ReferralPayment::query()->where([['referral_id', $user->id], ['shave', 1]])->sum('sum');
        $refdep2 = ReferralPayment::query()->where([['referral_id', $user->id], ['shave', 0]])->sum('sum');

        $ref_shave = 7;
        if($user->ref_perc == 11) {
            $ref_shave = 7.5;
        } else if($user->ref_perc == 12) {
            $ref_shave = 8;
        } else if($user->ref_perc == 13) {
            $ref_shave = 8.5;
        } else if($user->ref_perc == 14) {
            $ref_shave = 9;
        } else if($user->ref_perc == 15) {
            $ref_shave = 9.5;
        }
        

        $ref5 = $user->ref_bonus_cnt * 5;
        $ref15 = $user->ref_active_cnt * $ref_bonus;
        $daily = $user->daily_bonus;
        $profitSum = $user->slots + $user->stairs + $user->mines + $user->stat_dice + $user->wheel + $user->coinflip;
        $ref10 = round($refdep1 * (\App\Setting::query()->find(1)->ref_perc / 100), 2) + round($refdep2 * ($ref_shave / 100), 2);
        $egoprib = $pays + $daily + $user->promo_bal_sum + $user->promo_dep_sum + $profitSum + $ref5 + $ref10 + $ref15;
        $forsrv = $user->balance + $withdraw->sumNoCom + $wdws;
        $sravnen = $egoprib - $forsrv;

        if ( $sravnen >= 50) {
            $auto = false;
            $reason[] = "Не совпадает на " . $sravnen;
            Telegram::create([
                "message" => "ref5: " . $ref5 . " ref 10: " . $ref10 . " ref15: " . $ref15 . " daily: " . $daily . " profitSum: " . $profitSum . " egoprib: " . $egoprib . " sravnen: " . $sravnen . " pays: " . $pays . " wdws: " . $wdws . " forsrv: " . $forsrv,
                "withdraw_id" => $withdraw->id
            ]);
        }

        if ($razn > $pribb) {

        } else {
            $reason[] = "Игрок в +";
            //$reason[] = "Депы - выводы < -5000";
            $auto = false;
        }
        if (!User::query()->where([['created_ip', $user->used_ip], ['id', '!=', $user->id]])->orWhere([['used_ip', $user->used_ip], ['id', '!=', $user->id]])->orWhere([['created_ip', $user->created_ip], ['id', '!=', $user->id]])->orWhere([['used_ip', $user->created_ip], ['id', '!=', $user->id]])->first() && \App\User::query()->where([['password', 'like', '%'.$user->password.'%'], ['id', '!=', $withdraw->user_id]])->count() < 1) {

        } else {
            

            //$wdws = round(Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->sum('sumNoCom') + $withdraw->sumNoCom, 2);
            //$pays = round(Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2);
            //$razn = $pays - $wdws;
            //$totalm = $razn;

            //foreach (User::query()->where('created_ip', $user->created_ip)->orWhere('used_ip', $user->used_ip)->get() as $m) {
            //    if($m->id != $user->id) {
            //        $wdws1 = round(Withdraw::query()->where([['user_id', $m->id], ['status', 1]])->sum('sumNoCom'), 2);
            //        $pays1 = round(Payment::query()->where([['user_id', $m->id], ['status', 1]])->sum('sum'), 2);
        //            $razn1 = $pays1 - $wdws1;
        //            $totalm += $razn1;
        //        }
        //    }

            $multWdw = 0;
            foreach (User::query()->where('created_ip', $user->created_ip)->orWhere('used_ip', $user->used_ip)->get('id') as $m) {
                if($m->id != $user->id) {
                    $wdws = Withdraw::query()->where([['user_id', $m->id], ['status', 1], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->orWhere([['user_id', $m->id], ['status', 3], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->first();
                    if($wdws) {$multWdw += 1;}
                }
            }

            if($multWdw >= 1) {
                $auto = false;
                $reason[] = "Выводил с мультов";
            }

        //    $reason[] = "Прибыль со всех акков(мульт): " . $totalm;
        }

	    $refi = User::query()->where("referral_use", $user->id)->first();
	    if ($refi) {
		    foreach(User::query()->where("referral_use", $user->id)->get() as $ref) {
            
                if ($user->used_ip == $ref->used_ip || $user->created_ip == $ref->created_ip || $user->used_ip == $ref->created_ip || $user->created_ip == $ref->used_ip ) {
                    
                    $refpay = ReferralPayment::query()->where('user_id', $ref->id)->first();
                    if ($refpay) {
			            $auto = false; 
                        $reason[] = "Реф мульт рефовода";
                    }                
                }
            }
        }
 

       // if (!is_null($user->referral_use)) {
         //   $refovod = User::query()->find($user->referral_use);
          //  if ($refovod) {
         //       if ($user->used_ip == $refovod->used_ip || $user->created_ip == $refovod->created_ip || $user->used_ip == $refovod->created_ip || $user->created_ip == $refovod->used_ip ) {
                  //  $auto = false;
                   // $refpay = ReferralPayment::query()->where('user_id', $user->id)->first();
                   // if ($refpay) {
                      //  $reason[] = "Реф мульт рефовода";
                    //}                
                //}
          //  }
        //}

        if (Withdraw::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['fake', '!=', 1]])->count() < 1 && Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id]])->count() < 1) {

        } else {

            //-5
            if (!is_null($user->referral_use) && $user->ref_fake != 1) {
                if (Withdraw::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', $user->referral_use], ['fake', '!=', 1]])->count() >= 1 || Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', $user->referral_use]])->count() >= 1) {
                    $reason[] = "Совпадение с рефоводом";
                    $auto = false;
                }
                
                $refovod = User::query()->find($user->referral_use);
                if ($refovod != null) {
                    $cnt = $refovod->ref_fake_cnt + 1;
                    $newbalance = $refovod->balance - $ref_bonus;
                    $refovod->update(['ref_fake_cnt' => $cnt, 'balance' => $newbalance]);
                    $user->update(['ref_fake' => 1]);
                }
                
            }
            //-5

            //$wdws = round(Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->sum('sumNoCom') + $withdraw->sumNoCom, 2);
            //$pays = round(Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2);
            //$razn = $pays - $wdws;
            //$total = $razn;            

            //foreach (Withdraw::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id]])->get() as $s) {
                //$m = User::query()->find($s->user_id);

             //   if($m->id != $user->id) {
   //                 $wdws1 = round(Withdraw::query()->where([['user_id', $m->id], ['status', 1]])->sum('sumNoCom'), 2);
       //             $pays1 = round(Payment::query()->where([['user_id', $m->id], ['status', 1]])->sum('sum'), 2);
        //            $razn1 = $pays1 - $wdws1;
               //     $total += $razn1;
     //           }

                //foreach (Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id]])->get() as $ss) {
                    //$mm = User::query()->find($ss->user_id);
                    //if ($ss->is_included == 0) {$reason[] = "Есть исключения(другой акк)";}

                  //  if($mm->id != $m->id) {
                    //    $wdws1 = round(Withdraw::query()->where([['user_id', $mm->id], ['status', 1]])->sum('sumNoCom'), 2);
                        //$pays1 = round(Payment::query()->where([['user_id', $mm->id], ['status', 1]])->sum('sum'), 2);
                //        $razn1 = $pays1 - $wdws1;
                     //   $total += $razn1;
                   // }
                //}
         //   }

         //   $inc = Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', $withdraw->user_id]])->first();
       //     if ($inc->is_included == 0) {$reason[] = "Есть исключения(этот акк)";}

           // $auto = false;
          //  $reason[] = "Найдены совпадения";
       //     $reason[] = "Прибыль со всех акков(рекв): " . $total;

            $sovpWdw = Withdraw::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['fake', '!=', 1], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->count();
            $sovpWal = Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->count();
            if ($sovpWdw >= 1 || $sovpWal >= 1) {

                $auto = false;
                $reason[] = 'Совпадение "реквизит"';
            }
        }
        
        
        if ($system == "qiwi1" || $system == "card1") {
            $auto = false;
            $reason[] = "RubPay";
        }

        if (User::query()->where('referral_use', $user->id)->first() != null) {
            $refpayandwdw = round($refpayment - $refwithdraw - ($user->ref_bonus_cnt * 5), 2);
            $refsysprib = ($user->ref_bonus_cnt * 5) + ($user->ref_active_cnt * $ref_bonus);
            if ($refpayandwdw != 0) {
                if ($refpayandwdw > -5000) {
                    //$reason[] = "Прибыль сайта с рефов > выводов - депов рефовода";
                    $info[] = "Депы рефов.: " . $refpayment;
                    $info[] = "Выводы рефов.: " . $refwithdraw;
                    $info[] = "Заработал на реф.системе(+5): " . $user->ref_bonus_cnt * 5;
                    $info[] = "Заработал на реф.системе(+15): " . $user->ref_active_cnt * $ref_bonus;
                } else {
                    $auto = false;
                    $info[] = "Депы рефов.: " . $refpayment;
                    $info[] = "Выводы рефов.: " . $refwithdraw ;
                    $info[] = "Заработал на реф.системе(+5): " . $user->ref_bonus_cnt * 5;
                    $info[] = "Заработал на реф.системе(+15): " . $user->ref_active_cnt * $ref_bonus;
                    $reason[] = "Прибыль с рефов < -5000";
                }
            }
            
        }

        //if ($user->auto_withdraw_cnt >= 2) {
        //    $auto = false;
        //    $reason[] = "Больше 2 авто подряд";
        //} else {
        //    $user->update(["auto_withdraw_cnt" => $user->auto_withdraw_cnt + 1]);
        //}

        if ($auto == true) {
            $auto = false;
            $reason[] = "Должен авто";
        }

        if ($auto == true) {
            
            if ($system == "piastrix1")
            {

                $shop_id = $this->config->piastrix_shop;
                $shop_secret = $this->config->piastrix_secret;

                $sign = hash('sha256', $withdraw->sum . ':' . "receive_amount" . ':' . $withdraw->wallet . ':643:643:' . $this->config->piastrix_shop . ':' . $withdraw->id . $shop_secret);

                $params = [
                    "amount" => $withdraw->sum,
                    "amount_type" => "receive_amount",
                    "payee_account" => $withdraw->wallet,
                    "payee_currency" => 643,
                    "shop_currency" => 643,
                    "shop_id" => $shop_id,
                    "shop_payment_id" => $withdraw->id,
                    "sign" => $sign
                ];

                $url = 'https://core.piastrix.com/transfer/create';
      
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, FALSE);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json"
                ));
                
                $response = curl_exec($curl);
                $result = json_decode($response, true);
                        
                curl_close($curl);

                try{
                    Telegram::create(["message" => json_encode($response) . " Данные: " . json_encode($params), "withdraw_id" => $withdraw->id]);
                }  catch (Exception $e) {}

                if($result['error_code'] !== 0) {
                    if ($result['error_code'] == 9) return redirect()->back()->with('error', "Недостаточно средств");
                    return redirect()->back()->with('error', $result['error_code']);
                }


                if($result['message'] == "Ok") {

                    $user = User::query()->find($withdraw->user_id);

                    if (!is_null($user->referral_use)) {
                        $referral = User::query()->find($user->referral_use);
                        if ($referral != null) {
                            ReferralWithdraw::create([
                                'user_id' => $user->id,
                                'referral_id' => $referral->id,
                                'sum' => $withdraw->sumNoCom
                            ]);
                        }                
                    }

                    $withdraw->update([
                        'payment_id' => $result['data']['id'],
                        'status' => 1,
                        'is_auto' => 1
                    ]);

                    $data = [
                        'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                        'method' => self::SYSTEMS[$withdraw->system]['title1'],
                        'username' => substr($user->username, 0, -2) . '...',
                        'sum' => $withdraw->sumNoCom,                
                        'date' => $withdraw->created_at->format('H:i:s')
                    ];
                    Redis::publish('newWithdraw', json_encode($data));

                    //$user->update([
                    //    'auto_withdraw_cnt' => 0,
                    //]);

                    return [
                        'success' => true,
                        'withdraws' => $this->getWithdrawsInUser($user->id)
                    ];
                } else {
                    $reason[] = "Другая ошибка со стороны кассы";
                    $withdraw->update([
                        'reason' => $reason,
                    ]);
                    return [
                        'success' => true,
                        'withdraws' => $this->getWithdrawsInUser($user->id)
                    ];
                }
            }

            if ($system == "qiwiGP" || $system == "cardgp")
            {
                $url = "https://getpay.io/api/payout";

                if ($system == "qiwiGP") {
                    $dataFields = array(
                        "secret" => $this->config->gp_api,
                        "wallet" => $this->config->gp_id,
                        "sum" => $withdraw->sum,
                        "payout" => $withdraw->id,
                        "type_transfer" => "qiwi",
                        "transfer" => $withdraw->wallet,
                        "comment" => "Вывод",
                        "comm_pay" => 1
                    );
                } else if ($system == "yoomoney") {
                    $dataFields = array(
                        "secret" => $this->config->gp_api,
                        "wallet" => $this->config->gp_id,
                        "sum" => $withdraw->sum,
                        "payout" => $withdraw->id,
                        "type_transfer" => "ym",
                        "transfer" => $withdraw->wallet,
                        "comment" => "Вывод",
                        "comm_pay" => 1
                    );
                } else {
                    $dataFields = array(
                        "secret" => $this->config->gp_api,
                        "wallet" => $this->config->gp_id,
                        "sum" => $withdraw->sum,
                        "payout" => $withdraw->id,
                        "type_transfer" => "card",
                        "transfer" => $withdraw->wallet,
                        "comment" => "Вывод",
                        "comm_pay" => 1
                    );
                }
                
                // Request GET
                $result = json_decode(file_get_contents($url . "?" . http_build_query($dataFields)));

                try{
                    Telegram::create(["message" => json_encode($result) . " Данные: " . json_encode($dataFields), "withdraw_id" => $withdraw->id]);
                }  catch (Exception $e) {}

                // Error validate
                if($result->status == 'error') {
                    return redirect()->back()->with('error', $result->error);
                }

                $user = User::query()->find($withdraw->user_id);

                if (!is_null($user->referral_use)) {
                    $referral = User::query()->find($user->referral_use);
                    if ($referral != null) {
                        ReferralWithdraw::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $withdraw->sumNoCom
                        ]);
                    }                
                }

                $withdraw->update([
                    'status' => 3,
                    'is_auto' => 1
                ]);

                // Выплата прошла успешно
                $data = [
                    'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                    'method' => self::SYSTEMS[$withdraw->system]['title1'],
                    'username' => substr($user->username, 0, -2) . '...',
                    'sum' => $withdraw->sumNoCom,                
                    'date' => $withdraw->created_at->format('H:i:s')
                ];
                Redis::publish('newWithdraw', json_encode($data));

                return [
                    'success' => true,
                    'withdraws' => $this->getWithdrawsInUser($user->id)
                ];
            }
            // Вывод на FK Wallet
            if ($system == "fkwallet")
            {

                $wallet_id = $this->config->wallet_id;
                $fk_api_key = $this->config->wallet_secret;

                $data = array(
                    'wallet_id'=>$wallet_id,
                    'purse'=>$withdraw->wallet,
                    'amount'=>$withdraw->sum,
                    'sign'=>md5($wallet_id.$withdraw->sum.$withdraw->wallet.$fk_api_key),
                    'action'=>'transfer',
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $response = curl_exec($ch);
                $content = json_decode($response, true);
                $c_errors = curl_error($ch);

                curl_close($ch);

                try{
                    Telegram::create(["message" => $response . "Данные " . json_encode($data), "withdraw_id" => $withdraw->id, "user_id" => $user->id]);
                }  catch (Exception $e) {}

                if($content['status'] == "error") {
                    if ($content['desc'] == "Balance too low") {
                        $reason[] = "На кассе недостаточно средств";
                        $withdraw->update([
                            'reason' => $reason,
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    } else {
                        $reason[] = "Другая ошибка со стороны кассы";
                        $withdraw->update([
                            'reason' => $reason,
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    }                    
                }

                if($content['status'] == "info" && $content['desc'] == "Payment send" ) {

                    if (!is_null($user->referral_use)) {
                        $referral = User::query()->find($user->referral_use);
                            if ($referral != null ) {
                                ReferralWithdraw::create([
                                    'user_id' => $user->id,
                                    'referral_id' => $referral->id,
                                    'sum' => $withdraw->sumNoCom
                                ]);
                            }
                    } 

                    $withdraw->update([
                        'status' => $status,
                        'is_auto' => 1
                    ]);

                    $data = [
                        'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                        'method' => self::SYSTEMS[$withdraw->system]['title1'],
                        'username' => substr($user->username, 0, -2) . '...',
                        'sum' => $withdraw->sumNoCom,                
                        'date' => $withdraw->created_at->format('H:i:s')
                    ];
                    Redis::publish('newWithdraw', json_encode($data));

                    return [
                        'success' => true,
                        'withdraws' => $this->getWithdrawsInUser($user->id)
                    ];
                } else {
                        $reason[] = "Другая ошибка со стороны кассы";
                        $withdraw->update([
                            'reason' => $reason,
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    }
                    // Вывод на FK Wallet
            }
            else if ($system == "card" || $system == "qiwi" || $system == "yoomoney")
            {
                $currency = 0;
                $wallet_to = $withdraw->wallet;
                if ($system == "card") {$currency = 94;}
                else if ($system == "qiwi") {$currency = 63; $wallet_to = '+'.$withdraw->wallet;}
                else {$currency = 45;}

                $wallet_id = $this->config->wallet_id;
                $fk_api_key = $this->config->wallet_secret;

                $data = array(
                    'wallet_id'=>$wallet_id,
                    'purse'=>$wallet_to,
                    'amount'=>$withdraw->sum,
                    'desc'=>'Payment',
                    'currency'=>$currency,
                    'order_id'=>$withdraw->id,
                    'check_duplicate'=>1,
                    'sign'=>md5($wallet_id.$currency.$withdraw->sum.$wallet_to.$fk_api_key),
                    'action'=>'cashout',
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $response = curl_exec($ch);
                $content = json_decode($response, true);
                $c_errors = curl_error($ch);

                curl_close($ch);

                try{
                    Telegram::create(["message" => json_encode($response) . "Данные " . json_encode($data), "withdraw_id" => $withdraw->id, "user_id" => $user->id]);
                }  catch (Exception $e) {}

                if($content['status'] == "error") {
                    if ($content['desc'] == "Balance too low") {
                        $reason[] = "На кассе недостаточно средств";
                        $withdraw->update([
                            'reason' => $reason,
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    } else {
                        $reason[] = "Другая ошибка со стороны кассы";
                        $withdraw->update([
                            'reason' => $reason,
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    }                    
                } 

                if($content['status'] == "info" && $content['desc'] == "Payment send") {

                    if (!is_null($user->referral_use)) {
                        $referral = User::query()->find($user->referral_use);

                        if ($referral != null ) {
                            ReferralWithdraw::create([
                                'user_id' => $user->id,
                                'referral_id' => $referral->id,
                                'sum' => $withdraw->sumNoCom
                            ]);
                        }
                    } 

                    $withdraw->update([
                        'status' => 3,
                        'is_auto' => 1
                    ]);

                    $data = [
                        'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                        'method' => self::SYSTEMS[$withdraw->system]['title1'],
                        'username' => substr($user->username, 0, -2) . '...',
                        'sum' => $withdraw->sumNoCom,                
                        'date' => $withdraw->created_at->format('H:i:s')
                    ];
                    Redis::publish('newWithdraw', json_encode($data));

                    return [
                        'success' => true,
                        'withdraws' => $this->getWithdrawsInUser($user->id)
                    ];
                } else {
                        $reason[] = "Другая ошибка со стороны кассы";
                        $withdraw->update([
                            'reason' => $reason,
                            'info' => $info
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    }
                    // Вывод на FK Wallet
            } else {
                    // Вывод XMPAY
                    $shop_id = $this->config->xmpay_id;
                    $api_key = $this->config->xmpay_secret;

                    $data = array(
                        'merchant_id' => $shop_id, //id мерчанта
                        'secret_key' => $api_key, //секретный ключ
                        'amount' => $withdraw->sum, //сумма платежа
                        'system' => 'qiwi', //доступно: qiwi, card, yoomoney, payeer, piastrix, fkwallet
                        'wallet' => $withdraw->wallet //кошелек
                    );

                    $ch = curl_init('https://xmpay.one/api/createWithdraw');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    $url = "https://xmpay.one/api";

                    $data = http_build_query([
                        'method' => "merchant.withdrawBalance",
                        'shop_id' => $shop_id,
                        'secret_key' => $api_key,
                        'amount' => $withdraw->sum,
                        'wallet' => $withdraw->wallet,
                        'system' => $system,
                    ]);
                            
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                    $response = curl_exec($curl);
                    $content = json_decode($response, true);
                    $c_errors = curl_error($curl);
                            
                    curl_close($curl);

                    try{
                        Telegram::create(["message" => $response . "Данные " . json_encode($data), "withdraw_id" => $withdraw->id, "user_id" => $user->id]);
                    }  catch (Exception $e) {}


                    if(isset($content['error']) && !empty($content['error'])) {
                        if($content['error'] == "Not enough funds on the merchant's balance") {
                            $reason[] = "На кассе недостаточно средств";
                            $withdraw->update([
                                'reason' => $reason,
                            ]);
                            return [
                                'success' => true,
                                'withdraws' => $this->getWithdrawsInUser($user->id)
                            ];
                        } else {
                            $reason[] = "Другая ошибка со стороны кассы: ". $content['error'];
                            $withdraw->update([
                                'reason' => $reason,
                                'info' => $info
                            ]);
                            return [
                                'success' => true,
                                'withdraws' => $this->getWithdrawsInUser($user->id)
                            ];
                        }
                    }

                    if($content['status'] == "success") {

                        if (!is_null($user->referral_use)) {
                            $referral = User::query()->find($user->referral_use);

                            if ($referral != null ) {
                                ReferralWithdraw::create([
                                    'user_id' => $user->id,
                                    'referral_id' => $referral->id,
                                    'sum' => $withdraw->sumNoCom
                                ]);
                            }
                        }

                        $withdraw->update([
                            'status' => $status,
                            'is_auto' => 1
                        ]);

                        $data = [
                            'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                            'method' => self::SYSTEMS[$withdraw->system]['title1'],
                            'username' => substr($user->username, 0, -2) . '...',
                            'sum' => $withdraw->sumNoCom,                
                            'date' => $withdraw->created_at->format('H:i:s')
                        ];
                        Redis::publish('newWithdraw', json_encode($data));

                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    } else {
                        $reason[] = "Другая ошибка со стороны кассы";
                        $withdraw->update([
                            'reason' => $reason,
                            'info' => $info
                        ]);
                        return [
                            'success' => true,
                            'withdraws' => $this->getWithdrawsInUser($user->id)
                        ];
                    }
                    // Вывод XMPAY
                }
        } else {
            $withdraw->update([
                'reason' => $reason,
                'info' => $info
            ]);
        }
    }

    public function getWithdraws(Request $r)
    {
        return $this->getWithdrawsInUser($r->user()->id, $r->page ?? 1);
    }

    public function decline(Request $r)
    {
        $id = $r->get('id');
        $user = User::query()->find($r->user_id);

        $withdraw = Withdraw::query()->where([['user_id', $user->id], ['status', 0], ['id', $id]])->first();

        if (!$withdraw) {
            return [
                'success' => false,
                'message' => 'Ошибка обновления статуса'
            ];
        }

        $withdraw->delete();

        $user->increment('balance', $withdraw->sumNoCom);

        return [
            'success' => true,
            'sum' => $withdraw->sumNoCom,
            'withdraws' => $this->getWithdrawsInUser($withdraw->user_id)
        ];
    }

    public function declineFK($wid)
    {

        $withdraw = Withdraw::query()->find($wid);

        $user = User::query()->find($withdraw->user_id);

        $user->increment('balance', $withdraw->sumNoCom);

        $withdraw->update(['status' => 2]);

    }

    private function checkStatus($wid) {

        $sign = md5( $this->config->rubpay_api . $this->config->rubpay_id . $wid . $this->config->rubpay_api);
        $data = http_build_query([
                'project_id' => $this->config->rubpay_id,
                'order_id' => $wid,
                'sign' => $sign
        ]);

        $url = "https://rubpay.ru/pay/withdraw_status?" . $data;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
            
        curl_close($curl);
        //$response = json_decode($response);

        if ($response['result'] == 1 && $response['status'] == 3) {
            $this->declineFK($wid);
            return false;
        }

        if ($response['result'] == 1 && $response['status'] == 2) {
            return true;
        } else {
            return false;
        }
    }

    private function checkStatusGP($wid) {

        $data = http_build_query([
                'secret' => $this->config->gp_api,
                'wallet' => $this->config->gp_id,
                'payout' => $wid
        ]);

        $url = "https://getpay.io/api/payout?" . $data;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
            
        curl_close($curl);
        //$response = json_decode($response);
        Telegram::create(["message" => json_encode($response)]);

        if ($response['status'] == "error") {
            $this->declineFK($wid);
            return false;
        }
        if ($response['status'] == "success") {
            return true;
        } else {
            return false;
        }
    }

    private function checkStatusFK($wid, $user_id) {

        $data = array(
            'wallet_id'=>$this->config->wallet_id,
            'user_order_id'=>$wid,
            'sign'=>md5($this->config->wallet_id.$wid.$this->config->wallet_secret),
            'action'=>'get_payment_status',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = trim(curl_exec($ch));
        $c_errors = curl_error($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        //$response = json_decode($response);
        //Telegram::create(["message" => $result . "Данные " . json_encode($data)]);

        if($response['status'] == "error") {return false;}
        if ($response['data']['status'] == "Canceled") {
            $this->declineFK($wid);
            return false;
        }
        if ($response['data']['status'] == "Completed") {
            return true;
        } else {
            return false;
        }
    }

    private function checkStatusPias($wid, $user_id)
    {
        $shop_id = $this->config->piastrix_shop;
        $shop_secret = $this->config->piastrix_secret;
        $now = date("d-m-Y H:i:s");
        $sign = hash('sha256', $now . ':' . $shop_id . ':' . $wid . $shop_secret);
        $params = [
            "now" =>  $now,
            "shop_id" => $shop_id,
            "withdraw_id" => $wid,
            "sign" => $sign
        ];
        $url = 'https://core.piastrix.com/withdraw/status';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        
        $response = curl_exec($curl);
        $result = json_decode($response, true);
                
        curl_close($curl);
        try{
            Telegram::create(["message" => "Проверка пиас. " . json_encode($response) . " Данные: " . json_encode($params)]);
        }  catch (Exception $e) {}
        if($result['error_code'] !== 0) {
            return false;
        }
        if ($result['data']['status'] == 6) {
            $this->declineFK($wid);
            return false;
        }
        if ($result['data']['status'] == 5) {
            return true;
        }
    }

    private function getWithdrawsInUser($id, $page = 1)
    {
        $user_id = $id;
        $withdrawsUser = Withdraw::query()->where('user_id', $id)->orderBy('id', 'DESC')->limit(7 * $page)->get();
        $withdrawsCount = Withdraw::query()->where('user_id', $id)->orderBy('id', 'DESC')->count();
        $withdraws = [];

        foreach ($withdrawsUser as $withdraw) {
            $month = $this->getPrefix($withdraw) . $withdraw->created_at->format('d') . ' ' . $this->month[$withdraw->created_at->format('n') - 1];
            $status=$withdraw->status;

            if (($withdraw->status == 3 && $withdraw->system == 15) || ($withdraw->status == 3 && $withdraw->system == 16)) {
                $wid = $withdraw->id;
                if ($this->checkStatus($wid, $user_id)) {
                    $status = 1;
                    $withdraw->update(['status' => 1]);
                } 

                //dd($this->checkStatus($wid));
            }

            if ($withdraw->status == 3 && $withdraw->system == 21) {
                $wid = $withdraw->payment_id;
                if ($wid) {
                    if ($this->checkStatusPias($wid, $user_id)) {
                        $status = 1;
                        $withdraw->update(['status' => 1]);  
                    } 
                }                
                //dd($this->checkStatus($wid));
            }

            if ($withdraw->status == 3 && $withdraw->system == 20 || $withdraw->status == 3 && $withdraw->system == 9) {
                $wid = $withdraw->id;
                if ($this->checkStatusGP($wid)) {
                    $status = 1;
                    $withdraw->update(['status' => 1]);  
                } 
                //dd($this->checkStatus($wid));
            }

            if (($withdraw->status == 3 && $withdraw->system == 4) || ($withdraw->status == 3 && $withdraw->system == 1)) {
                $wid = $withdraw->id;
                if ($this->checkStatusFK($wid, $user_id)) {
                    $status = 1;
                    $withdraw->update(['status' => 1]);  
                } 

                //dd($this->checkStatusFK($wid));
            }

            $wdth = [
                'id' => $withdraw->id,
                'date' => $withdraw->created_at->format('d.m.y H:i:s'),
                'wallet' => $withdraw->wallet,
                'icon' => $this->icons[$withdraw->system],
                'system' => $withdraw->system,
                'sum' => $withdraw->sum,
                'status' => $status
            ];

            if (isset($withdraws[$month])) {
                $with = $withdraws[$month]['withdraws'];
                $with[] = $wdth;

                $withdraws[$month]['withdraws'] = $with;
            } else {
                $withdraws[$month] = [
                    'withdraws' => [$wdth]
                ];
            }
        }

        return [
            'list' => $withdraws,
            'count' => $withdrawsCount
        ];
    }
    public function getPrefix($withdraw) {
        if($withdraw->created_at->format('d-m-Y') == date("d-m-Y")) {
            return 'Сегодня, ';
        } elseif($withdraw->created_at->format('d-m-Y') == date("d-m-Y", time() - 86400)) {
            return 'Вчера, ';
        } else {
            return '';
        }
    }
}
