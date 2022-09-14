<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Session;
use App\Setting;
use App\Telegram;
use App\TopRefovods;
use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Exception;


class TelegramController extends Controller
{
    protected $telegram;
    protected $chat_id;
    protected $username;
    protected $text;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function clear()
    {
        return "OK";
    }

    public function rnd()
    {
        return Str::random(60);
    }

    public function session(Request $request)
    {
        session_start();

        $ref = null;
        $httpref = null;
        $auth_id = null;
        $token = $request['token'];

        if (isset($_SESSION['ref'])) {
            $ref = $_SESSION['ref'];            
        }

        if(isset($_SESSION['httpref'])) {
            $httpref = $_SESSION['httpref'];
        }

        if(isset($_SESSION['auth_id'])) {
            $auth_id = $_SESSION['auth_id'];
            unset($_SESSION['auth_id']);
        }

        $session = Session::query()->create([
            'token'=> $token,
            'ref_id' => $ref,
            'ip' => $this->getIp(),
            'httpref' => $httpref,
            'auth_id' => $auth_id
        ]);
        return "success";
    }

    public function handle(Request $request)
    {
        //session_start();
        //Telegram::create(['message' => $request->getContent()])->save();
        

        //$this->username = $request['message']['from']['username'];
        $this->username = $request['message']['chat']['id'];
        $this->chat_id = $request['message']['chat']['id'];
        $this->text = null;
        if (isset($request['message']['text'])) {
            $this->text = $request['message']['text'];
        } else {return "OK";}
        //$callback_query = $request['callback_query'];
        //$data1 = json_decode($callback_query['data'], true);

 
        if(strpos($this->text, '/start') !== false) {
            $textStrings = explode(' ', $this->text);

            if(isset($textStrings[1])) {
                $newtoken = $textStrings[1];

                if($newtoken) {

                    $session = Session::query()->where('token',$newtoken)->first();

                    if (!$session) {return "OK";}

                    if ($session->auth_id !== null) {
                        $userId = $session->auth_id;

                        $user = User::query()->find($userId);
                        $linkUserTG = User::query()->where('tg_id', $this->chat_id)->first();

                        if ($user && !$linkUserTG) {

                            $settings = Setting::query()->find('1')->first();

                            $balance = $user->balance + $settings->connect_bonus;

                            $user->update([
                                'is_tg' => 1,
                                'tg_id' => $this->chat_id,
                                'tg_username' => $this->username,
                                'balance' => $balance,
                                'used_ip' => $session->ip,
                                'api_token' => $newtoken
                            ]);
                            $data = array(
                                'chat_id' => $this->chat_id,
                                'text' => "Аккаунт успешно привязан! Возвращайтесь на сайт",
                                'disable_web_page_preview' => false,
                            );
                     
                            $this->sendMessage($data);
                            return "YES";
                        }

                    } else {
                        $user = User::query()->where([['tg_id', $this->chat_id]])->first();

                        if ($user) {
                            $token = $newtoken;

                            $user->update([
                                'tg_username' => $this->username,
                                'used_ip' => $session->ip,
                                'api_token' => $token
                            ]);
                        } else {

                            //reg
                            $username = 'tg' . $this->username;
                            $settings = Setting::query()->find('1')->first();

                            if (User::query()->where('tg_id', $this->chat_id)->first()) {
                                $data = array(
                                    'chat_id' => $this->chat_id,
                                    'text' => "Пользователь уже зарегистрирован",
                                    'disable_web_page_preview' => false,
                                );
                         
                                $this->sendMessage($data);
                                return "YES";
                            }

                            $token = $newtoken;

                            //$session = Session::query()->where('token',$token)->first();

                            $ref = $session->ref_id;

                            $buks = ['1','https://seo-fast.ru/', 'https://socpublic.com/', 'https://toloka.yandex.ru/', 'https://taskpay.ru/', 'https://qcomment.ru/', 'https://cashbox.ru/', 'https://profitcentr.com/', 'https://vktarget.ru/', 'https://profittask.com/', 'https://wmrfast.com/', 'http://www.wmmail.ru/', 'https://surfearner.com/', 'https://lamatop.com/', 'https://www.ipweb.ru/', 'https://aviso.bz/'];

                            if ($ref !== null) {
                                if (!User::query()->find($ref)) {
                                    $ref = null;
                                } else {
                                    $refer = User::query()->find($ref);
                                    if ($refer->is_ref_bonus === 1) {
                                        if (!User::query()->where('created_ip', $session->ip)->orWhere('used_ip', $session->ip)->first()) {
                                            if ($session->ip !== $refer->created_ip && $session->ip !== $refer->used_ip) {
                                                if(!array_search($session->httpref, $buks)) {
                                                    $refbalance = $refer->balance + 5;
                                                    $wager = $refer->wager + (5 * 3);
                                                    $refer->wager = $wager;
                                                    $refer->balance = $refbalance;                                            
                                                    $refer->ref_bonus_cnt += 1;
                                                }
                                            }
                                        }
                                    }
                                    $refer->link_reg += 1;
                                    $refer->save();

                                    $top = TopRefovods::query()->where('user_id', $ref)->first();
                                    if (!$top) {
                                        TopRefovods::create([
                                            'user_id' => $refer->id,
                                            'username' => $refer->username,
                                            'ref_cnt' => 1,
                                            'sum' => 5
                                        ]);
                                    } else {
                                        $top->update([
                                            'ref_cnt' => $top->ref_cnt + 1,
                                            'sum' => $top->sum + 5
                                        ]);
                                    }
                                }
                            }                            

                            $user = User::query()->create([
                                'username' => $username,
                                'api_token' => $token,
                                'created_ip' => $session->ip,
                                'used_ip' => $session->ip,
                                'httpref' => $session->httpref,
                                'is_tg' => 1,
                                'tg_id' => $this->chat_id,
                                'tg_username' => $username,
                                'tg_only' => 1,
                                'balance' => $settings->connect_bonus,
                                'referral_use' => json_encode($session->ref_id, JSON_NUMERIC_CHECK),
                            ]);

                            $data = array(
                                'chat_id' => $this->chat_id,
                                'text' => "Успешная регистрация! Возвращайтесь на сайт",
                                'disable_web_page_preview' => false,
                            );
                     
                            $this->sendMessage($data);

                            $keyboard = array(
                                array(
                                    array('text'=>'Получить промокод', 'url' => 'https://t.me/demoney_play')
                                )
                            );

                            $utf8emoji = 'F09F9187';

                            $data = array(
                                'chat_id' => $this->chat_id,
                                'text' => hex2bin($utf8emoji) . " Получай ежедневные промокоды в нашем канале " . hex2bin($utf8emoji),
                                'disable_web_page_preview' => false,
                                'reply_markup' => json_encode(array('inline_keyboard' => $keyboard)),
                            );

                            $this->sendMessage($data);
                            
                            return "YES";
                        }

                        $data = array(
                            'chat_id' => $this->chat_id,
                            'text' => "Успешная авторизация! Возвращайтесь на сайт",
                            'disable_web_page_preview' => false,
                        );
                     
                        $this->sendMessage($data);

                        $keyboard = array(
                            array(
                                array('text'=>'Получить промокод', 'url' => 'https://t.me/demoney_play')
                            )
                        );

                        $utf8emoji = 'F09F9187';

                        $data = array(
                            'chat_id' => $this->chat_id,
                            'text' => hex2bin($utf8emoji) . " Получай ежедневные промокоды в нашем канале " . hex2bin($utf8emoji),
                            'disable_web_page_preview' => false,
                            'reply_markup' => json_encode(array('inline_keyboard' => $keyboard)),
                        );

                        $this->sendMessage($data);
                        return "YES";
                    }   
                }
            } 
        } else {
            $data = array(
                'chat_id' => $this->chat_id,
                'text' => "Данной команды нет. Перейдите в бота через сайт и нажмите на 'ЗАПУСТИТЬ'",
                'disable_web_page_preview' => false,
            );
             
            $this->sendMessage($data);
        }       
    }

    public function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    protected function sendMessage($data, $parse_html = false)
    { 
        if ($parse_html) $data['parse_mode'] = 'HTML';
        
        try{
            $this->telegram->sendMessage($data);
        } catch (Exception $e) {
            return "No";
        }
        
    }
}
