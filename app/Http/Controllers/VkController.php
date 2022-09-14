<?php

namespace App\Http\Controllers;

use DigitalStar\vk_api\vk_api;
use Illuminate\Http\Request;
use App\User;
use App\Posts;
use DB;

class VkController extends Controller
{   
    protected $v = "5.131";
    protected $key = "c959381551c6dfc799dbc73e05407b290d38c216f6181a88849f0b1945af7667e88849aa49c8d3a9f70bb";
    protected $confirm = "0d21370d";
    protected $reward = 0.25;
    protected $wager = 13;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Request $r)
    {
        // $bot = vk_api::create($this->key, $this->v)->setConfirm($this->confirm);
        // $bot->initVars($peer_id, $message, $payload, $vk_id, $type, $data);
        
        // $btnStart = $bot->buttonText('Главное меню', 'blue', ['command' => 'start']);
        // $btnEarn = $bot->buttonText('Заработок', 'white', ['command' => 'earn']);
        // $btnBalance = $bot->buttonText('Баланс', 'white', ['command' => 'balance']);
        // $btnFaq = $bot->buttonOpenLink('FAQ', "https://vk.com/", ['command' => 'balance']);

        // switch($data->type) {
        //     case 'message_new':
        //         if(!$this->getUser($peer_id)) {
        //             return $bot->sendButton($peer_id, "
        //             Упс! 🤨 Что-то пошло не так...

        //             Для работы с ботом вам необходимо:
        //             1. Иметь аккаунт на сайте
        //             2. Привязать свой ВКонтакте к аккаунту
                    
        //             Ссылка на наш сайт 👉🏻 " . $_SERVER['HTTP_HOST'], [[$btnStart]]);
        //         }
        //         if(isset($payload)) {
        //             switch($payload['command']) {
        //                 case 'start':
        //                     $bot->sendButton($peer_id, "
        //                     Добро пожаловать, %a_full%! 
        //                     Нажми на кнопку Начать и начни зарабатывать реальные деньги! 
        //                     Ставь лайки, пиши комментарии и выводи деньги на любую платежную систему.
        //                     ", [[$btnEarn, $btnBalance], [$btnFaq]]);
        //                 break;
        //                 case 'earn':
        //                     $bot->sendButton($peer_id, "
        //                         Поставлено лайков: ".Posts::where([['owner_id', $peer_id], ['type', 'like_add']])->count()."
        //                         Написано комментариев: ".Posts::where([['owner_id', $peer_id], ['type', 'wall_reply_new']])->count()."
        //                         Количество репостов: ".Posts::where([['owner_id', $peer_id], ['type', 'wall_repost']])->count()."
        //                         Всего заработано: ".Posts::where('owner_id', $peer_id)->count() * $this->reward."
        //                         ", [[$btnStart]]);
        //                 break;
        //                 case 'balance':
        //                     $bot->sendButton($peer_id, "
        //                     Ваш баланс: ".$this->getUser($peer_id)->balance." ₽
        //                     ", [[$btnStart]]);
        //                 break;
        //                 case 'faq':

        //                 break;
        //             }
        //         } else {
        //             switch($message) {
        //                 default:
        //                     $bot->sendButton($peer_id, "Команда не распознана", [[$btnStart]]);
        //             }
        //         }
        //     break;
        //     case 'like_add':
        //         $owner_id = $data->object->liker_id;
        //         $type = $data->object->object_type;
        //         $post_id = $data->object->object_id;

        //         if($type == 'post') {
        //             if(!Posts::where([['post_id', $post_id], ['type', 'like_add'], ['owner_id', $owner_id]])->first()) {
        //                 Posts::create([
        //                     'owner_id' => $owner_id,
        //                     'post_id' => $post_id,
        //                     'type' => 'like_add'
        //                 ]);
        //                 User::where('vk_id', $owner_id)->update([
        //                     'balance' => DB::raw('balance + '. $this->reward),
        //                     'wager' => DB::raw('wager + '. $this->wager * $this->reward),
        //                 ]);
        //             }
        //         }
        //     break;
        //     case 'wall_reply_new':
        //         $owner_id = $data->object->from_id;
        //         $post_id = $data->object->post_id;

        //         if(!Posts::where([['post_id', $post_id], ['type', 'wall_reply_new'], ['owner_id', $owner_id]])->first()) {
        //             Posts::create([
        //                 'owner_id' => $owner_id,
        //                 'post_id' => $post_id,
        //                 'type' => 'wall_reply_new'
        //             ]);
        //             User::where('vk_id', $owner_id)->update([
        //                 'balance' => DB::raw('balance + '. $this->reward),
        //                 'wager' => DB::raw('wager + '. $this->wager * $this->reward),
        //             ]);
        //         }
        //     break;
        //     case 'wall_repost':
        //         $owner_id = $data->object->from_id;
        //         $post_id = $data->object->copy_history[0]->id;

        //         if(!Posts::where([['post_id', $post_id], ['type', 'wall_repost'], ['owner_id', $owner_id]])->first()) {
        //             Posts::create([
        //                 'owner_id' => $owner_id,
        //                 'post_id' => $post_id,
        //                 'type' => 'wall_repost'
        //             ]);
        //             User::where('vk_id', $owner_id)->update([
        //                 'balance' => DB::raw('balance + '. $this->reward),
        //                 'wager' => DB::raw('wager + '. $this->wager * $this->reward),
        //             ]);
        //         }
        //     break;
        // }

        return 'ok';
    }

    static function getUser($id)
    {
        $user = User::where('vk_id', $id)->first() ?? false;
        return $user;
    }
}
