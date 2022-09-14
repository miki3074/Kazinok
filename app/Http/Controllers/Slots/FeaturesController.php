<?php

namespace App\Http\Controllers\Slots;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class FeaturesController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
        $this->user = $this->auth;
    }

    public function getFeatures($request)
    {
        $request->data = (object) $request->data;

        $user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
                                
        $response = (object) [];
        $response->answer = (object) [];

        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->user_id = "$user->id";
        $response->answer->user_nickname = $user->username;
        $response->answer->balance = "$user->balance";
        $response->answer->bonus_balance = "0";      
        $response->answer->game_token = $user->game_token;
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->currency = "RUB";
        $response->answer->timestamp = '"'.time().'"';   

        // $response->answer->free_rounds = (object) [];
		// $response->answer->free_rounds->id = 0;
		// $response->answer->free_rounds->count = 0;
		// $response->answer->free_rounds->bet = 0;
		// $response->answer->free_rounds->lines = 0;	
		// $response->answer->free_rounds->mpl = 0;	
		// $response->answer->free_rounds->cp = "0";	
        // $response->answer->free_rounds->version = 2;

        $response->success = true;
        $response->api = $request->api;       
		        
        return $response; 
    }

    public function activateFeatures($request)
    {
        $request->data = (object) $request->data;

		$user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
		
        $response = (object) [];                           
        $response->answer = (object) [];			
		
        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->user_id = "$user->id";
        $response->answer->user_nickname = $user->username;
        $response->answer->balance = "$user->balance";
        $response->answer->bonus_balance = "0";
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->currency = "RUB";
        $response->answer->game_token = $user->game_token;
        $response->answer->timestamp = '"'.time().'"';
	
        $response->success = true;		    
        $response->api = $request->api;

        return $response; 
    }	
	
    public function updateFeatures($request)
    {
        $request->data = (object) $request->data;

		$user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
		
        $response = (object) [];                           
        $response->answer = (object) [];			
		
        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->user_id = "$user->id";
        $response->answer->user_nickname = $user->username;
        $response->answer->balance = "$user->balance";
        $response->answer->bonus_balance = "0";
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->currency = "RUB";
        $response->answer->game_token = $user->game_token;
        $response->answer->timestamp = '"'.time().'"';
	
        $response->success = true;		    
        $response->api = $request->api;	    
		        
        return $response; 
    }

    public function endFeatures($request)
    {
        $request->data = (object) $request->data;

		$user = $this->getUser($request->data->user_id, $request->data->user_game_token, $request->data->currency);
		
        $response = (object) [];                           
        $response->answer = (object) [];			
				
        $response->answer->operator_id = $this->OPERATOR_ID;
        $response->answer->user_id = "$user->id";
        $response->answer->user_nickname = $user->username;
        $response->answer->balance = "$user->balance";
        $response->answer->bonus_balance = "0";
        $response->answer->error_code = 0;
        $response->answer->error_description = "ok";
        $response->answer->currency = "RUB";
        $response->answer->game_token = $user->game_token;
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
}
