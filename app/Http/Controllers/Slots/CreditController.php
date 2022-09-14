<?php

namespace App\Http\Controllers\Slots;

use App\Http\Controllers\Controller;
use App\User;
use App\Profit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class CreditController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
        $this->user = $this->auth;
    }

    public function credit($request)
    {        

        $request->data = (object) $request->data;
        $user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
                
        $amount = $this->userUpdateAmount($request->data->user_id, $request->data->credit_amount, $request->data->currency);
        
        $response = (object) [];
        $response->answer = (object) [];
        
        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->transaction_id = $request->data->transaction_id; 
        $response->answer->user_id = "$user->id";
        $response->answer->user_nickname = $user->username;
        $response->answer->balance = "$amount";
        $response->answer->bonus_balance = "0";
        $response->answer->bonus_amount = "0";
        $response->answer->game_token = $user->game_token;
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->currency = "RUB";
        $response->answer->timestamp = '"'.time().'"';   
        
        $response->success = true;
        $response->api = $request->api;  
		        
        return $response;        
    }

    private function getUser($user_id, $game_token, $currency)
    {
        $user = User::where([['id', $user_id], ['game_token', $game_token]])->first();

        if(!$user) 
        {
            throw new Exception("User not found");
        }

        if($user->game_token !== $game_token)
        {
            throw new Exception("game_token not valid");
        }

        if($user->is_youtuber) {
            $this->OPERATOR_ID = $this->YT_OPERATOR_ID;
        }
        
        return $user;
    }

    private function userUpdateAmount($user_id, $credit_amount, $currency)
    {
        $user = User::where('id', $user_id)->first();
        $profit = Profit::query()->find(1);

        $user->balance += $credit_amount;
        $user->slots += $credit_amount;
        if(!$user->is_youtuber) {
            $profit->earn_slots -= $credit_amount;
            $profit->save();
        }
        $user->save();

        return $user->balance;
    }
}
