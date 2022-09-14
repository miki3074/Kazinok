<?php

namespace App\Http\Controllers;

use App\User;
use App\Payment;
use App\Withdraw;
use App\Telegram;
use App\ReferralPayment;
use App\ReferralWithdraw;
use Illuminate\Http\Request;
use DB;

class WithdrawController extends Controller
{
    const SYSTEMS = [
        1 => [
            'title' => 'ЮMoney',
            'comission' => 2
        ],
        2 => [
            'title' => 'Payeer',
            'comission' => 0 
        ],
        4 => [
            'title' => 'Qiwi - XMpay',
            'comission' => 2
        ],
        5 => [
            'title' => 'Beeline',
            'comission' => 0
        ],
        6 => [
            'title' => 'MegaFon',
            'comission' => 0
        ],
        7 => [
            'title' => 'MTS',
            'comission' => 0
        ],
        9 => [
            'title' => 'Карты - XMpay',
            'comission' => 1, //3%
            'plus' => 50
        ],
        10 => [
            'title' => 'MasterCard',
            'comission' => 0, //2%
            'plus' => 50
        ],
        11 => [
            'title' => 'Tele2',
            'comission' => 0
        ],
        12 => [
            'title' => 'FKWallet',
            'comission' => 0
        ],
        14 => [
            'title' => 'Piastrix',
            'comission' => 0
        ],
        15 => [
            'title' => 'Qiwi - RUBpay',
            'comission' => 3
        ],
        16 => [
            'title' => 'Карты - RUBpay',
            'comission' => 0, //3%
            'plus' => 50
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

            $sum = $r->get('sum');
            $wallet = strip_tags($r->get('wallet'));
            $system = $r->get('system');

            $secret_key = "oxyeli_g@ndoni?";

            if ($r->user()) {
                $hash = md5($sum.$r->user()->id.$secret_key.$sum.$r->time);

                if($hash != $r->a) {
                    //throw new \Exception(json_encode($game));
                    header('HTTP/1.1 403 Forbidden');
                    exit;
                }
            }            

            if ($this->config->fkwallet_only == 1 && $system != 12) {
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

            if ($r->user()->balance - $sum < 0 ) {
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

            if ($sum > $r->user()->balance) {
                return [
                    'success' => false,
                    'message' => 'Недостаточно средств на балансе'
                ];
            }

            if($r->user()->ban) {
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
            
            if ($system >= 4 && $system <= 7 || $system == 11 || $system == 15) {
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
            } else if ($system === 9 || $system === 16) {
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


            if(Payment::query()->where([['user_id', $r->user()->id], ['status', 1]])->sum('sum') < $this->config->min_dep_withdraw) return [
                'success' => false,
                'message' => 'Необходимо пополнить баланс на: ' . $this->config->min_dep_withdraw . ' руб'
            ];

            $pcount = Payment::query()->where([['user_id', $r->user()->id], ['status', 1]])->count(); //кол-во депов пользователя
            $psum = Payment::query()->where([['created_at', '>=', \Carbon\Carbon::today()->subDays($this->config->deposit_per_n)], ['user_id', $r->user()->id], ['status', 1]])->sum('sum');

            if($pcount >= 2 && $psum < $this->config->deposit_sum_n) return [
                'success' => false,
                'message' => 'Необходимо пополнить баланс на: ' . $this->config->deposit_sum_n . ' руб за последние ' . $this->config->deposit_per_n . ' дней'
            ];

            if(Withdraw::where('user_id', $r->user()->id)->where('status', 0)->count() >= $this->config->withdraw_request_limit) return [
                'success' => false,
                'message' => 'Дождитесь предыдущих выводов'
            ];

            if($r->user()->wager > 0) return [
                'success' => false,
                'message' => 'Вам необходимо отыграть еще ' . $r->user()->wager . ' руб'
            ];
            
            $sumNoCom = $sum;
            //$minus = isset(self::SYSTEMS[$system]['plus']) ? self::SYSTEMS[$system]['plus'] : 0;
            $minus = 0;
            $sumWithCom = $sum - ( $sum / 100 * self::SYSTEMS[$system]['comission'] ) - $minus;

            $wdrw = new Withdraw();
            $wdrw->user_id = $r->user()->id;            
            $wdrw->username = $r->user()->username;
            $wdrw->sum = $sumWithCom;
            $wdrw->sumNoCom = $sumNoCom;
            $wdrw->wallet = $wallet;
            $wdrw->system = $system;
            $wdrw->save();

            $r->user()->decrement('balance', $sumNoCom); //auto

            $user = $r->user();
            $wdrw_id = $wdrw->id;
            $this->tryAuto($wdrw_id, $user);

            return [
                'success' => true,
                'withdraws' => $this->getWithdrawsInUser($r->user()->id)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Выводы временно отключены'
            ];
        }
    }

     public function tryAuto($wdrw_id, $user)
    {
        $withdraw = Withdraw::query()->find($wdrw_id);
        
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
                $system = "card";
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

        if ($withdraw->sumNoCom <= 500) {

        } else {
            $auto = false;
            $reason[] = "Cумма > 500";
        }
        if (round(Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->sum('sumNoCom') + $withdraw->sumNoCom, 2) < round(\App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2)) {
        } else {
            $auto = false;
            $reason[] = "Все выводы + этот > депов";
        }
        if (!User::query()->where([['created_ip', $user->used_ip], ['id', '!=', $user->id]])->orWhere([['used_ip', $user->used_ip], ['id', '!=', $user->id]])->orWhere([['created_ip', $user->created_ip], ['id', '!=', $user->id]])->orWhere([['used_ip', $user->created_ip], ['id', '!=', $user->id]])->first()) {
        } else {
            $auto = false;
            $reason[] = "Найдены мульти";
        }
        if (Withdraw::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id]])->count() < 1) {
            
        } else {
            //-5
            if (!is_null($user->referral_use) && $user->ref_fake != 1) {

                $refovod = User::query()->find($user->referral_use);
                $cnt = $refovod->ref_fake_cnt + 1;
                $newbalance = $refovod->balance - 5;
                $refovod->update(['ref_fake_cnt' => $cnt, 'balance' => $newbalance]);

                $user->update(['ref_fake' => 1]);
            }
            //-5
            $auto = false;
            $reason[] = "Найдены совпадения";
        }



        if ($auto == true) {
            // Вывод на FK Wallet
            if ($system == "fkwallet") {

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

                //if($content['status'] == "success") {

                    if (!is_null($user->referral_use)) {
                        $referral = User::query()->find($user->referral_use);

                    ReferralWithdraw::create([
                        'user_id' => $user->id,
                        'referral_id' => $referral->id,
                        'sum' => $withdraw->sumNoCom
                    ]);
                    } 

                    $withdraw->update([
                        'status' => $status,
                        'is_auto' => 1
                    ]);

                    return [
                        'success' => true,
                        'withdraws' => $this->getWithdrawsInUser($user->id)
                    ];
                //}
                    // Вывод на FK Wallet
                } else if ($system == "card1" || $system == "qiwi1") {
//Вывод через RubPay

                    $shop_id = $this->config->rubpay_id;
                    $api_key = $this->config->rubpay_api;
                    $currency = 1;

                    $sign = md5( $api_key . $shop_id . $payment->id . $payment->sum . $currency . $api_key);

                    if ($system == "card1") {
                        $system = 7;
                    } else if ($system == "qiwi1") {
                        $system = 5;
                    }

                    $data = http_build_query([
                        'project_id' => $shop_id,
                        'amount' => $withdraw->sum,
                        'order_id' => $withdraw->id,
                        'sign' => $sign,
                        'currency' => $currency,
                        'payment_method' => $system,
                        'wallet' => $withdraw->wallet,
                        'withdraw_type' => 1
                    ]);

                    $url = "https://rubpay.ru/pay/withdraw?" . $data;
                    
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

                    $response = curl_exec($curl);
                    $content = json_decode($response, true);
                    
                    curl_close($curl);

                    if(isset($content['error']) && !empty($content['error'])) {
                        if($content['error'] == "Not enough funds on the merchant's balance") $content['error'] = 'На кассе недостаточно средств';
                        return redirect()->back()->with('error', $content['error']);
                    }

                    if($content['status'] == 3) {return redirect()->back()->with('error', "Произошла ошибка");}

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
                        'status' => $status,
                    ]);

                    return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');

//Вывод через RubPay
                }
                else {
                    // Вывод XMPAY
                    $shop_id = $this->config->xmpay_id;
                    $api_key = $this->config->xmpay_secret;

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
                        Telegram::create(["message" => $response . "Ошибки " . $c_errors . "Данные " . $data,]);
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

                            ReferralWithdraw::create([
                                'user_id' => $user->id,
                                'referral_id' => $referral->id,
                                'sum' => $withdraw->sumNoCom
                            ]);
                        }

                        $withdraw->update([
                            'status' => $status,
                            'is_auto' => 1
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
                    // Вывод XMPAY
                }
        } else {
            $withdraw->update([
                'reason' => $reason,
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

        $withdraw = Withdraw::query()->where([['user_id', $r->user()->id], ['status', 0], ['id', $id]])->first();

        if (!$withdraw) {
            return [
                'success' => false,
                'message' => 'Ошибка обновления статуса'
            ];
        }

        $withdraw->delete();

        $r->user()->increment('balance', $withdraw->sumNoCom);

        return [
            'success' => true,
            'sum' => $withdraw->sumNoCom,
            'withdraws' => $this->getWithdrawsInUser($withdraw->user_id)
        ];
    }

    private function getWithdrawsInUser($id, $page = 1)
    {
        $withdrawsUser = Withdraw::query()->where('user_id', $id)->orderBy('id', 'DESC')->limit(7 * $page)->get();
        $withdrawsCount = Withdraw::query()->where('user_id', $id)->orderBy('id', 'DESC')->count();
        $withdraws = [];

        foreach ($withdrawsUser as $withdraw) {
            $month = $this->getPrefix($withdraw) . $withdraw->created_at->format('d') . ' ' . $this->month[$withdraw->created_at->format('n') - 1];
            $wdth = [
                'id' => $withdraw->id,
                'date' => $withdraw->created_at->format('d.m.y H:i:s'),
                'wallet' => $withdraw->wallet,
                'icon' => $this->icons[$withdraw->system],
                'system' => $withdraw->system,
                'sum' => $withdraw->sum,
                'status' => $withdraw->status
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
