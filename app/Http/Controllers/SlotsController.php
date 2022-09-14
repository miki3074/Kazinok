<?php

namespace App\Http\Controllers;

use App\User;
use App\Slots;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class SlotsController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
        $this->user = $this->auth;
    }

    public function getGamesApi() {
        $url = 'https://int.apiforb2b.com/frontendsrv/apihandler.api?cmd={"api":"ls-games-by-operator-id-get","operator_id":"'. $this->OPERATOR_ID .'"}';
        $games = file_get_contents($url);
        $games = json_decode($games, true);
        foreach($games['locator']['groups'] as $group) {
            if ($group['gr_id'] != 15) {
                $prior = 0;
                if($group['gr_id'] == 22) {
                    $prior = 1;
                }
                foreach($group['games'] as $game) {
                    $icon = "https://int.apiforb2b.com/game/icons/" . $game['icons'][0]['ic_name'];
                    $id = $game['gm_bk_id'];
                    $title = $game['gm_title'];
                    if (!Slots::query()->where('game_id', $id)->first()) {
                        Slots::create([
                            "game_id" => $id,
                            "title" => $title,
                            "icon" => $icon,
                            "priority" => $prior
                        ]);
                    }
                }
            }
        }
        return "OK";
    }

    public function getGames(Request $r) 
    {
        $search = $r->search;
        $from = $r->from;
        $show = $r->show;

        $list = [];

        if(!$search) {
            $games = Slots::query()->offset($from)->limit($show)->orderBy('priority', 'desc')->orderBy('id', 'desc')->get();
    
            foreach($games as $game) {
                array_push($list, [
                    'game_id' => $game->game_id,
                    'title' => $game->title,
                    'icon' => $game->icon
                ]);
            }
        } else {
            $searchGames = Slots::query()->offset($from)->limit($show)->orderBy('id', 'asc')->where('title', 'like', '%' . $search . '%')->get();

            foreach($searchGames as $game) {
                array_push($list, [
                    'game_id' => $game->game_id,
                    'title' => $game->title,
                    'icon' => $game->icon
                ]);
            }
        }

        return [
            'success' => true,
            'list' => $list
        ];
    }

    public function getGameURI(Request $r) 
    {
        $gameId = $r->gameId;

        if(!$this->user) return [
            'error' => true,
            'msg' => 'Вы не авторизованы'
        ];

        //if($this->user->id == 20) return [
        //    'error' => true,
        //    'msg' => 'Дилшод, говорю же, ТОРМОЗИ!'
        //];

        if($this->user->ban) return [
            'error' => true,
            'msg' => 'Ваш аккаунт заблокирован'
        ];

        $url = "https://int.apiforb2b.com/gamesbycode/{$gameId}.gamecode?operator_id={$this->OPERATOR_ID}&user_id={$this->user->id}&auth_token={$this->user->api_token}&currency=RUB&language=ru&home_url=https://{$_SERVER['HTTP_HOST']}/slots";

        return [
            'success' => true,
            'url' => $url
        ];
    }

    public function callback(Request $request) 
    {
        try
        {
            switch($request->api) 
            {
                case 'do-auth-user-ingame':
                    $data = app('App\Http\Controllers\Slots\AuthController')->initAuth($request);
                    return json_encode($data);
                break;

                case 'do-debit-user-ingame':
                    $data = app('App\Http\Controllers\Slots\DebitController')->debit($request);
                    return json_encode($data);
                break;
    
                case 'do-credit-user-ingame':
                    $data = app('App\Http\Controllers\Slots\CreditController')->credit($request);
                    return json_encode($data);
                break;
            
                case 'do-rollback-user-ingame':
                    $data = app('App\Http\Controllers\Slots\RollbackController')->rollback($request);
                    return json_encode($data);
                break;        
    
                case 'do-get-features-user-ingame':
                    $data = app('App\Http\Controllers\Slots\FeaturesController')->getFeatures($request);
                    return json_encode($data);
                break;
    
                case 'do-activate-features-user-ingame':
                    $data = app('App\Http\Controllers\Slots\FeaturesController')->activateFeatures($request);
                    return json_encode($data);
                break;	

                case 'do-update-features-user-ingame':
                    $data = app('App\Http\Controllers\Slots\FeaturesController')->updateFeatures($request);
                    return json_encode($data);
                break;

                case 'do-end-features-user-ingame':
                    $data = app('App\Http\Controllers\Slots\FeaturesController')->endFeatures($request);
                    return json_encode($data);
                break;
    
                default :
                    throw new Exception("Unknown api");
            }
        }
        catch (Exception $e)
        {
            $response = (object) [];
            $response->answer = (object) [];
            $response->answer->error_code = 1;
            $response->answer->error_description = $e->getMessage();
            $response->answer->timestamp = '"'.time().'"';   
            $response->api = $request->api;
            $response->success = true;
            
            return json_encode($response);       
        }
    }
}
