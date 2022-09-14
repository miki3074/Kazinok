<?php

namespace App\Http\Controllers;

use App\Payment;
use App\User;
use App\Telegram;
use App\Setting;
use App\ReferralPayment;
use App\Promocode;
use App\PromocodeActivation;
use Illuminate\Http\Request;

use App\Withdraw;

use DB;

class PaymentController extends Controller
{
    protected $SYSTEMS = [
        'piastrix' => [
            'kassa' => 'xmpay',
            'method' => 'piastrix'
        ],
        'piastrix1' => [
            'kassa' => 'piastrix',
            'method' => 'piastrix'
        ],
        'qiwi' => [
            'kassa' => 'xmpay',
            'method' => 'qiwi'
        ],
        //'qiwi' => [
        //    'kassa' => 'swiftpay',
        //    'method' => 'qiwi'
        //],
        'qiwi1' => [
            'kassa' => 'rubpay',
            'method' => '4',
            'min' => 2000
        ],
        'qiwi2' => [
            'kassa' => 'getpay',
            'method' => 'qiwi'
        ],
        //'yoomoney' => [
        //    'kassa' => 'xmpay',
        //    'method' => 'yandex'
        //],
        'yoomoney' => [
            'kassa' => 'freekassa',
            'method' => '6'
        ],
        'alfa' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 300
        ],
        'raif' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 300
        ],
        'card' => [
            'kassa' => 'getpay',
            'method' => 'cardgp',
            'min' => 400
        ],
        'sber' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 400
        ],
        'sber1' => [
            'kassa' => 'rubpay',
            'method' => '11',
            'min' => 400
        ],
        'tinkoff' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 300
        ],
        'vtb' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 300
        ],
        'otkritie' => [
            'kassa' => 'rubpay',
            'method' => '1',
            'min' => 300
        ],
        'visa' => [
            'kassa' => 'xmpay',
            'method' => 'card'
        ],
        //'fkwallet' => [
        //    'kassa' => 'xmpay',
        //    'method' => 'fkwallet'
        //],
        'fkwallet' => [
            'kassa' => 'freekassa',
            'method' => '1'
        ],
        'visafk' => [
            'kassa' => 'freekassa',
            'method' => '8'
        ],
        'mobile' => [
            'kassa' => 'xmpay',
            'method' => 'mobile'
        ],
        //'vkpay' => [
        //    'kassa' => 'xmpay',
        //    'method' => 'vkpay'
        //],
        'steam' => [
            'kassa' => 'freekassa',
            'method' => ''
        ],
        'payeer' => [
            'kassa' => 'xmpay',
            'method' => 'payeer'
        ],
    ];

    public function __construct() 
    {
        parent::__construct();
        $this->user = $this->auth;
    }

    public function card($id)
    {
        $payment = Payment::query()->find($id);
        if ($payment && $payment->rubpay_rec_id != null) {
            $data = [
                'wallet' => $payment->to_wallet,
                'sum' => $payment->rubpay_sum,
                'type' => 'card',
                'link' => $payment->rubpay_link
            ];
            return $data;
        } else {
            $data = [
                'wallet' => $payment->to_wallet,
                'sum' => $payment->rubpay_sum,
                'type' => 'qiwi',
                'link' => $payment->rubpay_link
            ];
            return $data;
        }
        return redirect('/');
    }

    public function checkCard($id)
    {
        $data = http_build_query([
                'project_id' => $this->config->rubpay_id,
                'order_id' => $id
            ]);

        $url = "https://rubpay.ru/pay/status?" . $data;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
            
        curl_close($curl);
        //$response = json_decode($response);

        if ($response['result'] == 1 && $response['status'] == 2) {

            $payment = Payment::query()->find($id);

            if (!$payment || $payment->status) die('Not found payment');

            /*$payment->update([
                'status' => 1
            ]);

            $user = User::query()->find($payment->user_id);

            if ($user) 
            {
                $amount = $payment->sum;

                if($payment->code) 
                {
                    $promo = Promocode::query()->where('name', $payment->code)->first();
                    if($promo->id) 
                    {
                        $amount += $amount * ($promo->sum / 100);
                        $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    }
                }

                //$amount += $payment->sum * 0.03;
                $user->increment('balance', $amount);

                if (!is_null($user->referral_use)) {
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {
                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        } else if ($referral->id == 20) {
                            $this->config->ref_perc = 7;
                        } else if ($referral->id == 29544) {
                            $this->config->ref_perc = 5;
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));
                    }
                    ReferralPayment::create([
                        'user_id' => $user->id,
                        'referral_id' => $referral->id,
                        'sum' => $payment->sum,
                    ]);
                }
            }*/
            $data = ['success' => true];
            return $data;
        } elseif ($response['result'] == 0 && $response['error'] == "Payment not found") {
            $data = ['error' => true];
            return $data;
        } else {
            $data = ['success' => false];
            return $data;
        }
    }

    public function cancelCard($id)
    {
        $sign = md5( $this->config->rubpay_api . $this->config->rubpay_id . $id . $this->config->rubpay_api);
        $data = http_build_query([
                'order_id' => $id,
                'project_id' => $this->config->rubpay_id,
                'sign' => $sign
            ]);

        $url = "https://rubpay.ru/pay/cancel?" . $data;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
            
        curl_close($curl);
        //$response = json_decode($response);

        if ($response['result'] == 1) {

            $payment = Payment::query()->find($id);

            if (!$payment || $payment->status) die('Not found payment');

            $payment->update([
                'status' => 2
            ]);
            $data = ['success' => true];
            return $data;
        } elseif ($response['result'] == 0 && $response['error'] == "Payment not found") {
            $data = ['error' => true];
            return $data;
        }
    }

    public function create(Request $r)
    {
        if ($this->config->payments_on == 1 || $r->user()->is_admin == 1 || $this->config->fkwallet_only == 1) {

            $sum = $r->get('sum');
            $system = $r->get('system');
            $code = $r->get('promo');
            $num = $r->get('num');

            $kassa = $this->SYSTEMS[$system]['kassa'];
            $method = $this->SYSTEMS[$system]['method'];

            if (($this->config->xmpay_off == 1 && $method == "visa" && $r->user()->is_admin != 1 && $kassa == "xmpay") || ($this->config->xmpay_off == 1 && $method == "qiwi" && $r->user()->is_admin != 1  && $kassa == "xmpay")) {
               return [
                   'success' => false,
                   'message' => 'Данный метод временно отключен'
               ];
            }

            if ($kassa == "rubpay") {
                return [
                    'success' => false,
                    'message' => 'Данный метод временно отключен'
                ];
            }

            if ($method == "yandex") {
                return [
                    'success' => false,
                    'message' => 'Данный метод временно отключен'
                ];
            }

           if ($method == "qiwi2" && $r->user()->is_admin == 0) {
                return [
                    'success' => false,
                    'message' => 'Данный метод временно отключен'
                ];
            }

            if($sum < 500 && $method == "6") {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма через данный метод от 500 рублей'
                ];
            }
            if($sum < 1000 && $method == "8") {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма через данный метод от 1000 рублей'
                ];
            }

            if($sum < 400 && $method == "1" && $kassa == "rubpay") {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма через данный метод от 400 рублей'
                ];
            }
            if($sum < 400 && $method == "11" && $kassa == "rubpay") {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма через данный метод от 400 рублей'
                ];
            }
            if($sum > 5000 && $method == "qiwi") {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма через данный метод до 1000 рублей'
                ];
            }
            if($sum > 500 && $method == "card") {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма через данный метод до 500 рублей'
                ];
            }

            if($sum < 300 && $method == "cardgp") {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма через данный метод от 300 рублей'
                ];
            }

            if($sum > 5000 && $method == "cardgp") {
                return [
                    'success' => false,
                    'message' => 'Максимальная сумма через данный метод до 5000 рублей'
                ];
            }

            if ($this->config->fkwallet_only == 1 && $kassa != "freekassa" && $kassa != "piastrix") {
                return [
                    'success' => false,
                    'message' => 'Данный метод временно отключен'
                ];
            }

            //if ($this->SYSTEMS[$system]['kassa'] == "freekassa" && $r->user()->is_admin != 1) {
            //    return [
            //        'success' => false,
            //        'message' => 'Данный метод временно отключен'
            //    ];
            //}
            
            if($this->SYSTEMS[$system]['method'] == '') {
                return [
                    'success' => false,
                    'message' => 'Данный способ оплаты отключен'
                ];
            }

            if ($sum < $this->config->min_payment_sum) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма пополнения: ' . $this->config->min_payment_sum . ' руб.'
                ];
            }

            if($code) {
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

                $used = PromocodeActivation::query()->where([['promo_id', $promo->id], ['user_id', $this->user->id]])->first();

                if ($used) {
                    return [
                        'success' => false,
                        'message' => 'Вы уже использовали этот код'
                    ];
                }

                if ($promo->type == 'balance') {
                    return [
                        'success' => false,
                        'message' => 'Активируйте промокод в разделе бонусы'
                    ];
                }

                if(User::where([['created_ip', $this->user->created_ip], ['ban', 0]])->count() >= 5 || User::where([['used_ip', $this->user->used_ip], ['ban', 0]])->count() >= 5) {
                    return [
                        'success' => false,
                        'message' => 'У вас есть мультиаккаунты. Вы не можете активировать промокод'
                    ];
                }

                PromocodeActivation::query()->create([
                    'promo_id' => $promo->id,
                    'user_id' => $this->user->id,
                    'type' => 'deposit'
                ]);
            }
            

            if($kassa == 'rubpay' && $method == '4' && $sum < 1000) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма пополнения через данный метод: 1000 руб.'
                ];
            } else if ($kassa == 'rubpay' && $sum < 400) {
                return [
                    'success' => false,
                    'message' => 'Минимальная сумма пополнения через данный метод: 400 руб.'
                ];
            }

            $copy = Payment::query()->where([['system', 'rubpay'], ['status', 0], ['user_id', $r->user()->id]])->first();

            if ($copy != null) {
                $this->cancelCard($copy->id);
            }

            $payment = Payment::query()->create([
                'user_id' => $r->user()->id,
                'username' => $r->user()->username,
                'sum' => $sum,
                'code' => $code,
                'from_wallet' => $num,
                'system' => $kassa
            ]);

            switch($kassa) {
                case 'rubpay':
                    $currency = 1;
                        $sign = md5( $this->config->rubpay_api . $this->config->rubpay_id . $payment->id . $payment->sum . $currency . $this->config->rubpay_api);

                        if ($method == '4') {
                            $params = http_build_query([
                                'project_id' => $this->config->rubpay_id,
                                'amount' => $payment->sum,
                                'order_id' => $payment->id,
                                'sign' => $sign,
                                'payment_method' => 4,
                                'phone' => $payment->from_wallet,
                                'json' => 1,
                            ]);
                        } else if ($method == '11') {
                            $params = http_build_query([
                                'project_id' => $this->config->rubpay_id,
                                'amount' => $payment->sum,
                                'order_id' => $payment->id,
                                'sign' => $sign,
                                'json' => 1,
                                'payment_method' => 1,
                                'bank_name' => 'sber'
                            ]);
                        } else {
                            $params = http_build_query([
                                'project_id' => $this->config->rubpay_id,
                                'amount' => $payment->sum,
                                'order_id' => $payment->id,
                                'sign' => $sign,
                                'json' => 1,
                                'payment_method' => 1,
                                //'bank_name' => $system
                            ]);
                        }                    

                        $url = 'https://rubpay.ru/pay/create?' . $params;

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                
                        $response = curl_exec($curl);
                        $result = json_decode($response, true);
                        
                        curl_close($curl);

                        //dd($result);

                        /*if($result['errCode'] !== 0) return [
                            'success' => false,
                            'message' => $result['error']
                        ];*/

                        Telegram::create(["message" => json_encode($response)]);

                        if ($method == '4') {
                            $payment->update([
                                'to_wallet' => $result['card'],
                                'rubpay_id' => $result['payment_id'],
                                'rubpay_sum' => $result['amount'] + $result['fee'],
                                'rubpay_link' => $result['payment_link']
                            ]); // примерно

                            //$url = $result['payment_link'];
                            $url = "https://demoney.one/payment?id=".$payment->id;
                            break;
                        }
                        
                        $payment->update([
                            'to_wallet' => $result['card'],
                            'rubpay_id' => $result['payment_id'],
                            'rubpay_rec_id' => $result['receipt_id'],
                            'rubpay_sum' => $result['amount'] + $result['fee'],
                            'system' => $payment->system . " - " . $system,
                        ]);
                        $url = "https://demoney.one/payment?id=".$payment->id;
                break;
                case 'freekassa1':
                    $merchant_id = $this->config->kassa_id;
                    $secret_word = $this->config->kassa_secret1;
                    $order_id = $payment->id;
                    $order_amount = $payment->sum;
                    $currency = 'RUB';

                    if($method == '1') {
                        $payment->update([
                            'our_com' => $payment->sum * 1.04,
                        ]);
                        $order_amount = $payment->our_com;
                    } 

                    $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$currency.':'.$order_id);
            
                    $url = "https://pay.freekassa.ru/?m=".$merchant_id."&oa={$order_amount}&o={$order_id}&s=".$sign."&i=".$method."&currency=RUB&em=". $payment->user_id . "@mail.ru";
                break;
                case 'freekassa':
                    
                    $url = 'https://api.freekassa.ru/v1/orders/create';

                    $order_id = $payment->id;
                    $order_amount = $payment->sum;
                    $currency = 'RUB';

                    if($method == '1') {
                        $payment->update([
                            'our_com' => $payment->sum * 1.04,
                        ]);
                        $order_amount = $payment->our_com;
                    } else if($method == '6') {
                        $payment->update([
                            'our_com' => $payment->sum * 1.05,
                        ]);
                        $order_amount = $payment->our_com;
                    }

                    $params = [
                        'nonce' => $payment->id,
                        'shopId' => intval($this->config->kassa_id),
                        'paymentId' => $payment->id,
                        'i' => intval($method),
                        'ip' => $this->getIp(),
                        'currency' => $currency,
                        'amount' => $order_amount,
                        'email' => $payment->user_id . "@mail.ru",
                    ];

                    ksort($params);
                    $sign = hash_hmac('sha256', implode('|', $params), $this->config->kassa_key);
                    $params['signature'] = $sign;

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            
                    $response = curl_exec($curl);
                    $result = json_decode($response, true);
                    
                    curl_close($curl);

                    $url =$result['location'];
                break;
                case 'getpay':
                    $merchant_api = $this->config->gp_api;
                    $merchant_id = $this->config->gp_id;
                    $order_id = $payment->id;
                    $payment->update([
                        'our_com' => $payment->sum * 1.05,
                    ]);
                    $order_amount = $payment->our_com;

                    $url = "https://getpay.io/api/pay";
                    $dataFields = array(
                        "secret" => $merchant_api,
                        "wallet" => $merchant_id,
                        "sum" => $order_amount,
                        "order" => $order_id,
                        "resultUrl" => "https://demoney.bid/payment/getpay",
                        "backUrl" => "https://demoney.one/",
                        "comment" => "Пополнение баланса аккаунта #" . $payment->user_id . " на сайте"
                    );

                    // Request GET
                    $result = json_decode(file_get_contents($url . "?" . http_build_query($dataFields)));

                    // Error validate
                    if($result->status == 'error') {
                        die($result->error);
                    }

                    $payment->update([
                        'rubpay_id' => $result->paymentId,
                    ]);
                    if ($method == "qiwi") {$url = $result->redirectUrl . "?type=qiwi";} else {
                        $url = $result->redirectUrl . "?type=card";
                    }
                break;
                case 'xmpay':
                    $merchant_id = $this->config->xmpay_id;
                    $secret_word = $this->config->xmpay_public;
                    $order_id = $payment->id;
                    $order_amount = $payment->sum;

                    $payment->update([
                        'our_com' => $payment->sum * 1.02,
                    ]);
                    $order_amount = $payment->our_com;

                    $data = array(
                        'merchant_id' => $merchant_id,
                        'public_key' => $secret_word,
                        'amount' => $order_amount,
                        'label' => $order_id,
                        'system' => $method,
                    );

                    $ch = curl_init('https://xmpay.one/api/createOrder');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    $json_decode = json_decode($response, true);
                    $url = $json_decode['data']['url'];
                break;
                case 'swiftpay':
                    if($payment->sum < 100) return [
                        'success' => false,
                        'message' => 'Пополнения через данный метод от 100 рублей'
                    ];

                    $url = 'https://api.swiftpay.store/payIn/create';
                    $params = http_build_query([
                        'system' => $method,
                        'token' => $this->config->swift_api,
                        'shop_id' => $this->config->swift_shop,
                        'order_id' => $payment->id,
                        'amount' => $payment->sum,
                        'data' => [],
                        'email' => "SUPPORT@DEMONEY.ONE",
                    ]);

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            
                    $response = curl_exec($curl);
                    $result = json_decode($response, true);
                    
                    curl_close($curl);

                    Telegram::query()->create(['message' => json_encode($result), 'withdraw_id' => $payment->id]);

                    if($result['errCode'] !== 0) return [
                        'success' => false,
                        'message' => $result['error']
                    ];

                    $payment->update([
                        'rubpay_id' => $result['data']['id'],
                    ]);

                    $url = $result['data']['link'];
                break;
                case 'piastrix':

                    if($payment->sum < 100) return [
                        'success' => false,
                        'message' => 'Пополнения через данный метод от 100 рублей'
                    ];

                    $url = 'https://core.piastrix.com/bill/create';
                    $shop_secret = $this->config->piastrix_secret;

                    $payment->update([
                        'our_com' => $payment->sum * 1.04,
                    ]);
                    $order_amount = $payment->our_com;
                    $sign = hash('sha256', "643" . ':' . $order_amount . ':' . "643" . ':' . $this->config->piastrix_shop . ':' . $payment->id . $shop_secret);
                    
                    $params = [
                        "description" => "Bill #" . $payment->id,
                        "payer_currency" => "643",
                        "shop_amount" => $order_amount,
                        "shop_currency" => "643",
                        "shop_id" => $this->config->piastrix_shop,
                        "shop_order_id" => strval($payment->id),
                        "sign" => $sign
                    ];
                    
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
                    //Telegram::query()->create(['message'=>json_encode($result)]);

                    Telegram::query()->create(['message' => json_encode($response) . ", CURL: " . $curl . ", PARAMS: " . json_encode($params)]);

                    if($result['error_code'] !== 0) return [
                        'success' => false,
                        'message' => $result['error_code']
                    ];

                    $url = $result['data']['url'];
                break;
            }

            return [
                'success' => true,
                'url' => $url
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Платежи временно отключены'
            ];
        }
    }

    public function get(Request $r)
    {
        $paymentsUser = Payment::query()->where('user_id', $r->user()->id)->orderBy('id', 'desc')->limit(10)->get();
        $payments = [];

        foreach ($paymentsUser as $payment) {
            $status = 0;
            $pid = $payment->id;
            if ($payment->status == 1) {$status = "Успешно";}
            else if ($payment->status == 2) {$status = "Отменен";}
            else if ($payment->status == 0) {$status = "В ожидании";}
            if (($payment->system == "rubpay - sber" && $payment->status == 0) || ($payment->system == "rubpay - sber1" && $payment->status == 0) || ($payment->system == "rubpay" && $payment->status == 0)) {
                if ($this->checkStatus($pid)) $status = "Лимит времени";
            }
            $payments[] = [
                'id' => $payment->id,
                'time' => $payment->updated_at->format('d.m.y H:i:s'),
                'sum' => $payment->sum,
                'status' => $status
            ];
        }

        return $payments;
    }

    private function checkStatus($pid) {

        $data = http_build_query([
                'project_id' => $this->config->rubpay_id,
                'order_id' => $pid,
        ]);

        $url = "https://rubpay.ru/pay/status?" . $data;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curl);
        $response = json_decode($response, true);
            
        curl_close($curl);
        //$response = json_decode($response);

        if ($response['result'] == 1 && $response['status'] == 4) {
            return true;
        } else {
            return false;
        }
    }

    public function RubPayHandle(Request $r) {
        $checkhash = md5( $r->project_id . $r->order_id . $r->payment_id . $r->amount . $r->currency . $r->status . $this->config->rubpay_api );
        if($checkhash != $r->hash) return die('Hacking attempt!');
        
        $payment = Payment::query()->find($r->order_id);
        if (!$payment || $payment->status == 1) die('OK');
        //if (!$payment || $payment->status && $payment->status != 2) die('OK');
        //if (intval($payment->sum) !== intval($r->amount)) die('Invalid sum');

        if ($payment->status == 0) {

            $user = User::query()->find($payment->user_id);

            if ($user) 
            {
                $amount = $r->amount;

                if($payment->code) 
                {
                    $promo = Promocode::query()->where('name', $payment->code)->first();
                    if($promo->id) 
                    {
                        $amount += $amount * ($promo->sum / 100);
                        $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    }
                }

                //$amount += $payment->sum * 0.03;
                $user->increment('balance', $amount);
                $user->increment('wager', $amount);

                $payment->update([
                    'status' => 1,
                    'rubpay_sum' => $r->amount
                ]);

                if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                        if ($referral->is_ref_bonus === 1) {
                            if ($user->ref_bonus_received == 0) {
                                if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                        $user->increment('ref_bonus_received', 1);
                                        $referral->increment('balance', $this->config->ref_bonus);
                                        $referral->increment('wager', $this->config->ref_bonus);
                                    }
                                }
                            }
                        }

                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $firstpay = Payment::query()->where('user_id', $user->id)->first();
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            if($firstpay) {
                                $origin = date_create($firstpay->created_at);
                                $target = date_create('now');
                                $interval = date_diff($origin, $target);
                                $interval = $interval->format('%a');

                                if ( $interval > 21 && $paycnt >= 3) {
                                    $this->config->ref_perc = 7;
                                    if($referral->ref_perc == 11) {
                                        $this->config->ref_perc = 7.5;
                                    } else if($referral->ref_perc == 12) {
                                        $this->config->ref_perc = 8;
                                    } else if($referral->ref_perc == 13) {
                                        $this->config->ref_perc = 8.5;
                                    } else if($referral->ref_perc == 14) {
                                        $this->config->ref_perc = 9;
                                    } else if($referral->ref_perc == 15) {
                                        $this->config->ref_perc = 9.5;
                                    }
                                    $shave = 1;
                                }
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
            }

            die('OK');

        } else {die("Перевод уже зачислен");}

    }

    public function swiftHandle(Request $r)
    {
        Telegram::query()->create(['message' => json_encode($r->all()), 'withdraw_id' => $r->id]);

        $checkhash = hash('sha256', $r->order_id . $r->amount . $this->config->swift_api . $this->config->swift_shop);
        if(mb_strtoupper($checkhash) !== mb_strtoupper($r->sign)) die('Hacking attempt!');
        if($r['status']['code'] !== 1) die('Not paid');

        $payment = Payment::query()->find($r->order_id);
        if (!$payment || $payment->status) die('Not found payment');
        if ((intval($payment->sum) !== intval($r->amount)) && (intval($payment->sum*1.12) !== intval($r->amount))) die('Invalid sum');

        $user = User::query()->find($payment->user_id);

        if ($user) 
        {
            $amount = $payment->sum;

            if($payment->code) 
            {
                $promo = Promocode::query()->where('name', $payment->code)->first();
                if($promo->id) 
                {
                    $amount += $amount * ($promo->sum / 100);
                    $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                }
            }

            $amount += $payment->sum * 0.03;
            $user->increment('balance', $amount);
            $user->increment('wager', $amount);

            $payment->update([
                'status' => 1
            ]);

            if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                        if ($referral->is_ref_bonus === 1) {
                            if ($user->ref_bonus_received == 0) {
                                if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                        $user->increment('ref_bonus_received', 1);
                                        $referral->increment('balance', $this->config->ref_bonus);
                                        $referral->increment('wager', $this->config->ref_bonus);
                                    }
                                }
                            }
                        }

                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $firstpay = Payment::query()->where('user_id', $user->id)->first();
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            if($firstpay) {
                                $origin = date_create($firstpay->created_at);
                                $target = date_create('now');
                                $interval = date_diff($origin, $target);
                                $interval = $interval->format('%a');

                                if ( $interval > 21 && $paycnt >= 3) {
                                    $this->config->ref_perc = 7;
                                    if($referral->ref_perc == 11) {
                                        $this->config->ref_perc = 7.5;
                                    } else if($referral->ref_perc == 12) {
                                        $this->config->ref_perc = 8;
                                    } else if($referral->ref_perc == 13) {
                                        $this->config->ref_perc = 8.5;
                                    } else if($referral->ref_perc == 14) {
                                        $this->config->ref_perc = 9;
                                    } else if($referral->ref_perc == 15) {
                                        $this->config->ref_perc = 9.5;
                                    }
                                    $shave = 1;
                                }
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
        }

        die('SUCCESS');
    }

    public function getpayHandle(Request $r)
    {
        Telegram::query()->create(['message' => json_encode($r->all()), 'withdraw_id' => $r->ORDER_ID]);

        $sign = md5($r->WALLET_ID.':'.$r->SUM.':'.$r->ORDER_ID.':'.$this->config->gp_api);

        if($sign != $r->SIGN) die('Hacking attempt!');

        $payment = Payment::query()->find($r->ORDER_ID);
        if (!$payment || $payment->status) die('Not found payment');
        if (intval($payment->sum) !== intval($r->SUM) && intval($payment->our_com) !== intval($r->SUM)) die('Invalid sum');

        $user = User::query()->find($payment->user_id);

        if ($user) 
        {
            $amount = $payment->sum;

            if($payment->code) 
            {
                $promo = Promocode::query()->where('name', $payment->code)->first();
                if($promo->id) 
                {
                    $amount += $amount * ($promo->sum / 100);
                    $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    $user->increment('promo_dep_sum', $promo->sum / 100);
                }
            }

            //rang

            if($user->rang_dep > 0) {
                $amount += $amount * ($user->rang_dep / 100);
                $user->increment('wager', ($payment->sum * ($user->rang_dep / 100)) * 16);
            }

            //rang

            //$amount += $payment->sum * 0.03;
            $user->increment('balance', $amount);
            $user->increment('wager', $amount);

            $user->increment('rang_points', $amount); //rang

            $payment->update([
                'status' => 1
            ]);

            $this->RangUp($user); //rang

            if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                      //  if ($referral->is_ref_bonus === 1) {
                        //    if ($user->ref_bonus_received == 0) {
                             //   if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                //    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                 //       $user->increment('ref_bonus_received', 1);
                                  //      $referral->increment('balance', $this->config->ref_bonus);
                                    //    $referral->increment('wager', $this->config->ref_bonus);
                                //    }
                             //   }
                          //  }
                    //    }

                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            $origin = date_create($user->created_at);
                            $target = date_create('now');
                            $interval = date_diff($origin, $target);
                            $interval = $interval->format('%a');
                            if ( $interval > 21 && $paycnt >= 3) {
                                $this->config->ref_perc = 7;
                                if($referral->ref_perc == 11) {
                                    $this->config->ref_perc = 7.5;
                                } else if($referral->ref_perc == 12) {
                                    $this->config->ref_perc = 8;
                                } else if($referral->ref_perc == 13) {
                                    $this->config->ref_perc = 8.5;
                                } else if($referral->ref_perc == 14) {
                                    $this->config->ref_perc = 9;
                                } else if($referral->ref_perc == 15) {
                                    $this->config->ref_perc = 9.5;
                                }
                                $shave = 1;
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
        }

        die('OK');
    }

    public function RangUp($user)
    {

        if ($user->rang_points >= 3000 && $user->rang_1 != 1) {
            $user->increment('balance', 100);
            $user->increment('wager', 1000);
            $user->update([
                "rang_1" => 1,
                "current_rang" => 1
            ]);
            \Log::info("Выдан 1 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }

        if ($user->rang_points >= 10000 && $user->rang_2 != 1) {
            $user->increment('balance', 250);
            $user->increment('wager', 2500);
            $user->update([
                "rang_2" => 1,
                "current_rang" => 2
            ]);
            \Log::info("Выдан 2 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }

        if ($user->rang_points >= 25000 && $user->rang_3 != 1) {
            $user->increment('balance', 400);
            $user->increment('wager', 4000);
            $user->update([
                "rang_3" => 1,
                "current_rang" => 3
            ]);
            \Log::info("Выдан 3 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }

        if ($user->rang_points >= 60000 && $user->rang_4 != 1) {
            $user->increment('balance', 1000);
            $user->increment('wager', 10000);
            $user->update([
                "rang_4" => 1,
                "current_rang" => 4
            ]);
            \Log::info("Выдан 4 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }

        if ($user->rang_points >= 100000 && $user->rang_5 != 1) {
            $user->increment('balance', 2000);
            $user->increment('wager', 20000);
            $user->update([
                "rang_5" => 1,
                "rang_dep" => 2,
                "current_rang" => 5
            ]);
            \Log::info("Выдан 5 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }

        if ($user->rang_points >= 250000 && $user->rang_6 != 1) {
            $user->increment('balance', 5000);
            $user->increment('wager', 50000);
            $user->update([
                "rang_6" => 1,
                "rang_dep" => 3,
                "current_rang" => 6
            ]);
            \Log::info("Выдан 6 ранг пользователю #" . $user->id . "Баланс: " . $user->balance . "Вагер: " . $user->wager);
        }
    }

    public function handle(Request $r)
    {
        Telegram::query()->create(['message' => json_encode($r->all()), 'withdraw_id' => $r->intid]);

        if(!in_array($this->getIp(), ['168.119.157.136', '168.119.60.227', '138.201.88.124', '136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '136.243.38.108']))
            die('hacking attempt!');
        $sign = md5($r->MERCHANT_ID.':'.$r->AMOUNT.':'.$this->config->kassa_secret2.':'.$r->MERCHANT_ORDER_ID);
        if($sign != $r->SIGN) die('wrong sign!');

        $payment = Payment::query()->find($r->MERCHANT_ORDER_ID);
        if (!$payment || $payment->status) die('Not found payment');
        if (intval($payment->sum) !== intval($r->AMOUNT) && intval($payment->our_com) !== intval($r->AMOUNT)) die('Invalid sum');

        $user = User::query()->find($payment->user_id);

        if ($user) 
        {
            $amount = $payment->sum;

            if($payment->code) 
            {
                $promo = Promocode::query()->where('name', $payment->code)->first();
                if($promo->id)
                {
                    $amount += $amount * ($promo->sum / 100);
                    $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    $user->increment('promo_dep_sum', $promo->sum / 100);
                }
            }

            //rang

            if($user->rang_dep > 0) {
                $amount += $amount * ($user->rang_dep / 100);
                $user->increment('wager', ($payment->sum * ($user->rang_dep / 100)) * 16);
            }

            //rang

            //$amount += $payment->sum * 0.03;
            $user->increment('balance', $amount);
            $user->increment('wager', $amount);

            $user->increment('rang_points', $amount); //rang

            $payment->update([
                'status' => 1
            ]);

            $this->RangUp($user); //rang

            if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                        //  if ($referral->is_ref_bonus === 1) {
                        //    if ($user->ref_bonus_received == 0) {
                             //   if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                //    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                 //       $user->increment('ref_bonus_received', 1);
                                  //      $referral->increment('balance', $this->config->ref_bonus);
                                    //    $referral->increment('wager', $this->config->ref_bonus);
                                //    }
                             //   }
                          //  }
                    //    }

                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            $origin = date_create($user->created_at);
                            $target = date_create('now');
                            $interval = date_diff($origin, $target);
                            $interval = $interval->format('%a');
                            if ( $interval > 21 && $paycnt >= 3) {
                                $this->config->ref_perc = 7;
                                if($referral->ref_perc == 11) {
                                    $this->config->ref_perc = 7.5;
                                } else if($referral->ref_perc == 12) {
                                    $this->config->ref_perc = 8;
                                } else if($referral->ref_perc == 13) {
                                    $this->config->ref_perc = 8.5;
                                } else if($referral->ref_perc == 14) {
                                    $this->config->ref_perc = 9;
                                } else if($referral->ref_perc == 15) {
                                    $this->config->ref_perc = 9.5;
                                }
                                $shave = 1;
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
        }

        die('YES');
    }

    public function piastrixHandle(Request $r) {
        

        if(!in_array($this->getIp(), ['51.68.53.104', '51.68.53.105', '51.68.53.106', '51.68.53.107', '37.48.108.180', '37.48.108.181']))
            die('hacking attempt!');

        $signed_data = $r->client_price . ':' . $r->created . ':' . $r->description .':' . $r->payment_id . ':' . $r->payway . ':' . $r->processed . ':' . $r->ps_currency . ':' . $r->ps_data . ':' . $r->shop_amount . ':' . $r->shop_currency . ':' . $r->shop_id . ':' . $r->shop_order_id . ':' . $r->shop_refund . ':' . $r->status . $this->config->piastrix_secret;
        $checkhash = hash('sha256', $signed_data);

        Telegram::query()->create(['message' => json_encode($r->all()) . " Хэш: " . $checkhash . " Строка: " . $signed_data]);

        
        if($checkhash != $r->sign) return die('Hacking attempt!');
        
        $payment = Payment::query()->find($r->shop_order_id);
        if (!$payment || $payment->status) die('Not found payment');
        //if (intval($payment->sum) !== intval($r->amount)) die('Invalid sum');

        $user = User::query()->find($payment->user_id);

        if ($user) 
        {
            $amount = $payment->sum;

            if($payment->code) 
            {
                $promo = Promocode::query()->where('name', $payment->code)->first();
                if($promo->id) 
                {
                    $amount += $amount * ($promo->sum / 100);
                    $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    $user->increment('promo_dep_sum', $promo->sum / 100);
                }
            }

            //rang

            if($user->rang_dep > 0) {
                $amount += $amount * ($user->rang_dep / 100);
                $user->increment('wager', ($payment->sum * ($user->rang_dep / 100)) * 16);
            }

            //rang

            //$amount += $payment->sum * 0.03;
            $user->increment('balance', $amount);
            $user->increment('wager', $amount);

            $user->increment('rang_points', $amount); //rang

            $payment->update([
                'status' => 1
            ]);

            $this->RangUp($user); //rang

            if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                        //  if ($referral->is_ref_bonus === 1) {
                        //    if ($user->ref_bonus_received == 0) {
                             //   if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                //    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                 //       $user->increment('ref_bonus_received', 1);
                                  //      $referral->increment('balance', $this->config->ref_bonus);
                                    //    $referral->increment('wager', $this->config->ref_bonus);
                                //    }
                             //   }
                          //  }
                    //    }


                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            $origin = date_create($user->created_at);
                            $target = date_create('now');
                            $interval = date_diff($origin, $target);
                            $interval = $interval->format('%a');
                            if ( $interval > 21 && $paycnt >= 3) {
                                $this->config->ref_perc = 7;
                                if($referral->ref_perc == 11) {
                                    $this->config->ref_perc = 7.5;
                                } else if($referral->ref_perc == 12) {
                                    $this->config->ref_perc = 8;
                                } else if($referral->ref_perc == 13) {
                                    $this->config->ref_perc = 8.5;
                                } else if($referral->ref_perc == 14) {
                                    $this->config->ref_perc = 9;
                                } else if($referral->ref_perc == 15) {
                                    $this->config->ref_perc = 9.5;
                                }
                                $shave = 1;
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
        }

        die('OK');
    }

    public function xmHandle(Request $r) {
        $checkhash = hash('sha512', $r->merchant_id.'|'.$this->config->xmpay_secret.'|'. $r->label.'|'.$r->amount);
        Telegram::query()->create(['message' => json_encode($r->all()), 'withdraw_id' => $r->id]);
        if($checkhash != $r->sign) return die('Hacking attempt!');
        
        $payment = Payment::query()->find($r->label);
        if (!$payment || $payment->status) die('Not found payment');
        if (intval($payment->sum) !== intval($r->amount) && intval($payment->our_com) !== intval($r->amount)) die('Invalid sum');

        $user = User::query()->find($payment->user_id);

        if ($user) 
        {
            $amount = $payment->sum;

            if($payment->code) 
            {
                $promo = Promocode::query()->where('name', $payment->code)->first();
                if($promo->id) 
                {
                    $amount += $amount * ($promo->sum / 100);
                    $user->increment('wager', ($payment->sum * ($promo->sum / 100)) * $promo->wager);
                    $user->increment('promo_dep_sum', $promo->sum / 100);
                }
            }

            //rang

            if($user->rang_dep > 0) {
                $amount += $amount * ($user->rang_dep / 100);
                $user->increment('wager', ($payment->sum * ($user->rang_dep / 100)) * 16);
            }

            //rang

            //$amount += $payment->sum * 0.03;
            $user->increment('balance', $amount);
            $user->increment('wager', $amount);

            $user->increment('rang_points', $amount); //rang

            $payment->update([
                'status' => 1
            ]);

            $this->RangUp($user); //rang

            if (!is_null($user->referral_use)) {
                    $shave = 0;
                    $referral = User::query()->find($user->referral_use);

                    if ($referral) {

                        //  if ($referral->is_ref_bonus === 1) {
                        //    if ($user->ref_bonus_received == 0) {
                             //   if (!User::query()->where('created_ip', $user->used_ip)->orWhere('used_ip', $user->used_ip)->first()) {
                                //    if ($user->used_ip !== $refer->created_ip && $user->created_ip !== $refer->used_ip && $user->created_ip !== $refer->created_ip && $user->used_ip !== $refer->used_ip) {
                                 //       $user->increment('ref_bonus_received', 1);
                                  //      $referral->increment('balance', $this->config->ref_bonus);
                                    //    $referral->increment('wager', $this->config->ref_bonus);
                                //    }
                             //   }
                          //  }
                    //    }


                        if($referral->ref_perc > 0) {
                            $this->config->ref_perc = $referral->ref_perc;
                        }

                        if ($amount > 250) {
                            $paycnt = Payment::query()->where('user_id', $user->id)->count();

                            $origin = date_create($user->created_at);
                            $target = date_create('now');
                            $interval = date_diff($origin, $target);
                            $interval = $interval->format('%a');
                            if ( $interval > 21 && $paycnt >= 3) {
                                $this->config->ref_perc = 7;
                                if($referral->ref_perc == 11) {
                                    $this->config->ref_perc = 7.5;
                                } else if($referral->ref_perc == 12) {
                                    $this->config->ref_perc = 8;
                                } else if($referral->ref_perc == 13) {
                                    $this->config->ref_perc = 8.5;
                                } else if($referral->ref_perc == 14) {
                                    $this->config->ref_perc = 9;
                                } else if($referral->ref_perc == 15) {
                                    $this->config->ref_perc = 9.5;
                                }
                                $shave = 1;
                            }
                        }

                        $referral->increment('balance', $payment->sum * ($this->config->ref_perc / 100));

                        $paymentSum = $payment->sum;
                        //if ($referral->id == 20) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (53.85 / 100), 0);
                        //} else if ($referral->id == 29544) {
                        //    $paymentSum = $paymentSum - round($paymentSum * (38.46 / 100), 0);
                        //}

                        ReferralPayment::create([
                            'user_id' => $user->id,
                            'referral_id' => $referral->id,
                            'sum' => $paymentSum,
                            'shave' => $shave
                        ]);

                        $top = \App\TopRefovods::query()->where('user_id', $referral->id)->first();

                        if ($top) {
                            $top->update(['sum' => $top->sum + $payment->sum * ($this->config->ref_perc / 100)]);
                        }
                    }
                }
        }

        die('YES');
    }

    public function getIP()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }
}
