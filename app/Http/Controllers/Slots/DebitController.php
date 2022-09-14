<?php

namespace App\Http\Controllers\Slots;

use App\Http\Controllers\Controller;
use App\User;
use App\Profit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class DebitController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
        $this->user = $this->auth;
    }

    public function debit($request)
    {        
        $request->data = (object) $request->data;
        $user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
        
		if ($user->balance < $request->data->debit_amount)
        {
            throw new Exception("Not enought amount");
        }
        
        $amount = $this->userUpdateAmount($request->data->user_id, $request->data->debit_amount, $request->data->currency);
        $this->userUpdateGameTokenDate($request->data->user_id);

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

    private function userUpdateAmount($user_id, $debit_amount, $currency)
    {
        $user = User::where('id', $user_id)->first();
        $profit = Profit::query()->find(1);

        $user->balance -= $debit_amount;
        $user->slots -= $debit_amount;
        if(!$user->is_youtuber) {
            $profit->earn_slots += $debit_amount;
            $profit->save();
        }
        $user->save();

        if($user->wager < 0 || $user->wager - $debit_amount < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $debit_amount);
        }

        return $user->balance;
    }

    private function userUpdateGameTokenDate($user_id)
    {
        $user = User::where('id', $user_id)->first();
        
        $user->game_token_date = now();
        $user->save();
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
}
