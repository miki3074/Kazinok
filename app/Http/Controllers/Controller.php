<?php

namespace App\Http\Controllers;

use App\User;
use App\Setting;
use App\ReferralPayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use App\Http\Middleware\EncryptCookies;
use Auth;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $config;
    protected $auth;
    protected $token;
    protected $OPERATOR_ID = 20703;
    protected $YT_OPERATOR_ID = 20706;

    protected $PROVIDERS = [
        "Netent",
        "YggDrasil",
        "Igrosoft",
        "Novomatic Deluxe",
        "BET IN HELL",
        "Belatra",
        "Unicum",
        "Megajack",
        "Playtech",
        "Play'n GO",
        "Microgaming"
    ];
    
    public function __construct()
    {
        $this->token = Cookie::get('token') ?? '';
        $this->auth = User::where('api_token', $this->token)->first();
        $this->config = Setting::query()->find(1);

        if($this->auth && $this->auth->is_youtuber) {
            $this->OPERATOR_ID = $this->YT_OPERATOR_ID;
        }

        view()->share('settings', $this->config);
        view()->share('u', $this->auth);
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function curl($url) 
    {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
	}

    public function tooFast() 
    {
        if($this->auth) 
        {
            if(\Cache::has('action.' . $this->auth->id)) return true;
            \Cache::put('action.' . $this->auth->id, '', 1);
        }
        return false;
    }

    public function tooFastNew($id) 
    {
        if(\Cache::has('action.' . $id)) return true;
        \Cache::put('action.' . $id, '', 1.5);
        return false;
    }
}
