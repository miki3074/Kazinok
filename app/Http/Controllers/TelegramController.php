<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use App\Telegram;
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
 
    public function getMe()
    {
        $response = $this->telegram->getMe();
        return $response;
    }

    public function setWebHook()
    {
        $url = 'https://demoney.bid/' . env('TELEGRAM_BOT_TOKEN') . '/webhook';
        $response = $this->telegram->setWebhook(['url' => $url]);
     
        return $response == true ? redirect()->back() : dd($response);
    }

    public function handleRequest(Request $request)
    {
        $this->chat_id = $request['message']['chat']['id'];
        $this->username = $request['message']['from']['username'];
        $this->text = $request['message']['text'];
        $callback_query = $request['callback_query'];
        $data = json_decode($callback_query['data'], true);

        //Telegram::create(['message' => $data['id']])->save();
 
        switch ($this->text) {
            case '/start':

            break;
        }
    }
 
    public function showMenu($info = null)
    {
        $rekv = '+799999999';
        $summ = '500';
        //$rekv = $withdraw->wallet;
        //$summ = $withdraw->sum;
        $id = 1;

        $keyboard = array(
          array(
             array('text'=>'Подтвердить','callback_data'=>json_encode([
                 'status' => 1,
                 'id' => $id
             ])),
             array('text'=>'Отменить','callback_data'=>json_encode([
                'status' => 2,
                'id' => $id
            ]))
          )
        );

        $data = array(
            'chat_id' => $this->chat_id,
            'text' => "Реквизиты: " . $rekv . "\n" . "Сумма: " . $summ,
            'disable_web_page_preview' => false,
            'reply_markup' => json_encode(array('inline_keyboard' => $keyboard)),
        );
 
        $this->sendMessage($data);
    }
 
    protected function sendMessage($data, $parse_html = false)
    { 
        if ($parse_html) $data['parse_mode'] = 'HTML';
 
        $this->telegram->sendMessage($data);
    }
}
