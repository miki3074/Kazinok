<?php

namespace App\Http\Controllers\Slots;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class AuthController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
        $this->user = $this->auth;
    }

    public function initAuth($request) 
    {
        $request->data = (object) $request->data;

        $auth_token = $request->data->user_auth_token;
                
        $user = $this->getUser($request->data->user_id, $request->data->user_auth_token, $request->data->currency);
                
		$game_token = md5(time() . mt_rand(1,1000000));
		       
		$this->initSession($request->data->user_id, $game_token, $request->data->currency);
        
        $response = (object) [];
        $response->answer = (object) [];
        $response->answer->balance = "$user->balance";
        $response->answer->bonus_balance = "0";        
        $response->answer->user_id = "{$user->id}";
        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->currency = $request->data->currency;
        $response->answer->user_nickname = $user->username;
        $response->answer->auth_token = $request->data->user_auth_token;
        $response->answer->game_token = $game_token; 
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->timestamp = '"'.time().'"';   		
        $response->api = $request->api;
        $response->success = true;
		        
        return $response;
    }

    private function initSession($user_id, $game_token, $currency)
    {
        $user = User::where('id', $user_id)->first();

        if(!$user) 
        {
            throw new Exception("User not found");
        }

        $user->game_token = $game_token;
        $user->game_token_date = now();
        $user->save();

    }

    public function getUser($user_id, $auth_token, $currency)
    {
        $user = User::where([['id', $user_id], ['api_token', $auth_token]])->first();

        if(!$user) 
        {
            throw new Exception("User not found");
        }

        if($user->api_token !== $auth_token)
        {
            throw new Exception("auth_token not valid");
        }

        if($user->is_youtuber) {
            $this->OPERATOR_ID = $this->YT_OPERATOR_ID;
        }

        return $user;
    }
}
