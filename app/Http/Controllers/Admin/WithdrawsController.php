<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Withdraw;
use App\ReferralWithdraw;
use App\User;
use App\AdminLogs;
use Illuminate\Support\Facades\Cookie;

//use Telegram\Bot\Api;
use App\Telegram;
use Carbon\Carbon;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WithdrawsController extends Controller
{
    protected $telegram;
    protected $chat_id = 325095587;
    protected $auth;
    protected $token;

const SYSTEMS = [
        //1 => [
        //    'title' => 'ЮMoney',
        //    'title1' => 'ЮMoney',
        //    'comission' => 2
        //],
        1 => [
            'title' => 'ЮMoney - FK',
            'title1' => 'ЮMoney',
            'comission' => 4 //4
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
        20 => [
            'title' => 'Qiwi - GP',
            'title1' => 'QIWI',
            'comission' => 2 //4%
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
            'comission' => 2, //4%
            'plus' => 0 //50
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
            'comission' => 2, //3%
            'plus' => 0
        ],
        21 => [
            'title' => 'Piastrix',
            'title1' => 'Piastrix',
            'comission' => 3
        ],

    ];

    public function __construct()
    {
        parent::__construct();
        $this->token = Cookie::get('token') ?? '';
        $this->auth = User::where('api_token', $this->token)->first();
        //$this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function log($log_role, $log_action, $log_request) {
        if ($this->auth->id != 6) {
            AdminLogs::query()->create([
                "user_id" => $this->auth->id,
                "role" => $log_role,
                "action" => $log_action,
                "request" => $log_request
            ]);
        }
    }
    
    public function index()
    {
        return view('admin.withdraws.index');
    }

    public function allIndex()
    {
        return view('admin.fullwithdraws.index');
    }

    public function setStatus($id, $status)
    {
        $withdraw = Withdraw::query()->find($id);

        if(!$withdraw) return redirect()->back()->with('error', 'Выплата отменена пользователем');
        if($withdraw->status > 0) return redirect()->back()->with('error', 'Статус выплаты уже изменен ранее');

        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "На вывод #" . $id . " установлен статус: " . $status;
        $log_request = "Запрос: " . $id . ' ' . $status;

        $this->log($log_role, $log_action, $log_request);

        if($status == 2) {
            $user = User::where('id', $withdraw->user_id)->first();
            $user->balance += Withdraw::where('id', $id)->first()->sumNoCom;
            $user->save();
            $withdraw->update([
                'status' => $status
            ]);
            return redirect()->back()->with('success', 'Выплата #'.$id.' отменена!');
        }

        $system = 0;
        $system1 = 0;

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
            //
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
            //
            default:
                return redirect()->back()->with('error', 'Платежная система не найдена');
            break;
        }

        if(User::query()->find($withdraw->user_id)->is_youtuber) {
            $withdraw->update([
                'status' => $status,
                'fake' => 1
            ]);
            return redirect()->back()->with('success', 'Статус выплаты изменен на "Отправлено"');
        }
// Вывод на киви
        // if ($system == "qiwi") {
        //     $rekv = $withdraw->wallet;
        //     $summ = $withdraw->sum;

        //     $keyboard = array(
        //       array(
        //          array('text'=>'Подтвердить','callback_data'=>json_encode([
        //              'status' => 1,
        //              'id' => $id
        //          ])),
        //          array('text'=>'Отменить','callback_data'=>json_encode([
        //             'status' => 2,
        //             'id' => $id
        //         ]))
        //       )
        //     );

        //     $data = array(
        //         'chat_id' => $this->chat_id,
        //         'text' => "Реквизиты: " . $rekv . "\n" . "Сумма: " . $summ,
        //         'disable_web_page_preview' => false,
        //         'reply_markup' => json_encode(array('inline_keyboard' => $keyboard)),
        //     );
     
        //     $this->sendMessage($data);

        //     $withdraw->update([
        //         'status' => '3',
        //     ]);

        //     return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена в обменник!');
        // }
// Вывод на киви

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
            //dd($content);

            curl_close($ch);

            try{
                Telegram::create(["message" => $response . "Данные: " . $data,]);
            }  catch (Exception $e) {}

            if($content['status'] == "error" && $content['desc'] == "Balance too low") {
                $content['desc'] = 'На кассе недостаточно средств';
                return redirect()->back()->with('error', $content['desc']);
            }

            //if ($content['status'] == "info" && $content['desc'] == "Payment send") {
            //    $withdraw->update([
            //        'status' => $status,
            //    ]);
            //}

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

            //$user->update([
            //    'auto_withdraw_cnt' => 0,
            //]);

            $data = [
                'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                'method' => self::SYSTEMS[$withdraw->system]['title1'],
                'username' => substr($user->username, 0, -2) . '...',
                'sum' => $withdraw->sumNoCom,                
                'date' => $withdraw->created_at->format('H:i:s')
            ];
            Redis::publish('newWithdraw', json_encode($data));

            return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
        }

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
                Telegram::create(["message" => json_encode($response) . " Данные: " . json_encode($params)]);
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
                    'status' => $status,
                ]);

                //$user->update([
                //    'auto_withdraw_cnt' => 0,
                //]);

                $data = [
                    'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                    'method' => self::SYSTEMS[$withdraw->system]['title1'],
                    'username' => substr($user->username, 0, -2) . '...',
                    'sum' => $withdraw->sumNoCom,                
                    'date' => $withdraw->created_at->format('H:i:s')
                ];
                Redis::publish('newWithdraw', json_encode($data));

                return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
            } else {return redirect()->back()->with('error', "Какая та ошибка со стороны кассы");}
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
                Telegram::create(["message" => json_encode($result) . " Данные: " . json_encode($dataFields), "withdraw_id" => $id]);
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
            return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
        }

        if ($system == "card" || $system == "qiwi" || $system == "yoomoney")
        {

            $wallet_id = $this->config->wallet_id;
            $fk_api_key = $this->config->wallet_secret;

            $currency = 0;
            $wallet_to = $withdraw->wallet;
            if ($system == "card") {$currency = 94;}
            else if ($system == "qiwi") {$currency = 63; $wallet_to = '+'.$withdraw->wallet;}
            else {$currency = 45;}

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
            //dd($content);

            curl_close($ch);

            try{
                Telegram::create(["message" => $response . "Данные: " . $data,]);
            }  catch (Exception $e) {}

            if($content['status'] == "error" && $content['desc'] == "Balance too low") {
                $content['desc'] = 'На кассе недостаточно средств';
                return redirect()->back()->with('error', $content['desc']);
            }

            //if ($content['status'] == "info" && $content['desc'] == "Payment send") {
            //    $withdraw->update([
            //        'status' => $status,
            //    ]);
            //}

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
            ]);

            $user->update([
                'auto_withdraw_cnt' => 0,
            ]);
            $data = [
                'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                'method' => self::SYSTEMS[$withdraw->system]['title1'],
                'username' => substr($user->username, 0, -2) . '...',
                'sum' => $withdraw->sumNoCom,                
                'date' => $withdraw->created_at->format('H:i:s')
            ];
            Redis::publish('newWithdraw', json_encode($data));
            return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
        }
// Вывод на FK Wallet

//Вывод через RubPay

        if ($system == "card1" || $system == "qiwi1") {

            $shop_id = $this->config->rubpay_id;
            $api_key = $this->config->rubpay_api;
            $currency = 1;
            $withdraw_type = 1;

            if ($system == "card1") {
                $system = 7;
            } else if ($system == "qiwi1") {
                $system = 5;
            }

            $sign = md5($api_key . $shop_id . $withdraw->sum . $withdraw->id . $currency . $system . $withdraw->wallet . $withdraw_type . $api_key);

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
            $c_errors = curl_error($curl);

            $response = curl_exec($curl);
            $content = json_decode($response, true);
        
            
            curl_close($curl);

            try{
                Telegram::create(["message" => "resp:". json_encode($content) . "Данные: " . $data . "errors:". json_encode($c_errors) ,]);
            }  catch (Exception $e) {}

            if(isset($content['error']) && !empty($content['error'])) {
                return redirect()->back()->with('error', $content['error']);
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
            ]);

            $user->update([
                'auto_withdraw_cnt' => 0,
            ]);

            //$data = [
              //  'wallet' => substr($withdraw->wallet, 0, -5) . '...',
        //        'method' => self::SYSTEMS[$withdraw->system]['title1'],
   //             'username' => substr($user->username, 0, -2) . '...',
      //          'sum' => $withdraw->sumNoCom,                
       //         'date' => $withdraw->created_at->format('H:i:s')
    //        ];
   //         Redis::publish('newWithdraw', json_encode($data));
            return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
        }

//Вывод через RubPay
        if ($system == "card2" || $system == "qiwi2") {
            $shop_id = $this->config->xmpay_id;
            $api_key = $this->config->xmpay_secret;

            if ($system == "qiwi2") {$system = "qiwi";} else if ($system == "fkwallet2") {$system = "fkwallet";} else if ($system == "card2") {$system = "card";}

            $data = array(
                'merchant_id' => $shop_id, //id мерчанта
                'secret_key' => $api_key, //секретный ключ
                'amount' => $withdraw->sum, //сумма платежа
                'system' => $system, //доступно: qiwi, card, yoomoney, payeer, piastrix, fkwallet
                'wallet' => $withdraw->wallet //кошелек
                );

            $ch = curl_init('https://xmpay.one/api/createWithdraw');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $content = json_decode($response, true);
            $c_errors = curl_error($ch);
            curl_close($ch);
            
            
            Telegram::create(["message" => "[XMPay]" . $response]);

            if(isset($content['error']) && !empty($content['error'])) {
                if($content['error'] == "Not enough funds on the merchant's balance") $content['error'] = 'На кассе недостаточно средств';            
                return redirect()->back()->with('error', $content['error']);
            }

            if(!$content['success']) {
                return redirect()->back()->with('error', $content['data']['message']);
            }

            if($content['success']) {
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

                $user->update([
                    'auto_withdraw_cnt' => 0,
                ]);

                $data = [
                    'wallet' => substr($withdraw->wallet, 0, -5) . '...',
                    'method' => self::SYSTEMS[$withdraw->system]['title1'],
                    'username' => substr($user->username, 0, -2) . '...',
                    'sum' => $withdraw->sumNoCom,                
                    'date' => $withdraw->created_at->format('H:i:s')
                ];
                Redis::publish('newWithdraw', json_encode($data));
                return redirect()->back()->with('success', 'Выплата #'.$id.' отправлена!');
            } else {return redirect()->back()->with('error', "Произошла другая ошибка");}
        }
    }

    public function handlePayment(Request $request)
    {
        $callback_query = $request['callback_query'];
        $response = json_decode($callback_query['data'], true);

        $withdraw = Withdraw::query()->find($response['id']);
        if($withdraw->status > 0 && $withdraw->status !== 3) return;
        $withdraw->update([
            'status' => $response['status'],
        ]);

        if( $response['status'] == '1' ) {
            
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

            $data = array(
                'chat_id' => $this->chat_id,
                'text' => "Заказ #" . $response['id'] . " успешно подтвержден! ",
                'disable_web_page_preview' => false,
            );
        }
        
        if( $response['status'] == '2' ) {

            //$user = User::where('id', $withdraw->user_id)->first();
            //$user->balance += Withdraw::where('id', $id)->first()->sumNoCom;
            //$user->save();

            $data = array(
                'chat_id' => $this->chat_id,
                'text' => "Заказ #" . $response['id'] . " успешно отменен! ",
                'disable_web_page_preview' => false,
            );
        }
        $this->sendMessage($data);     
    }

    protected function sendMessage($data, $parse_html = false)
    {
        if ($parse_html) $data['parse_mode'] = 'HTML';
 
        $this->telegram->sendMessage($data);
    }
}
