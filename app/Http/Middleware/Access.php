<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use App\Http\Middleware\EncryptCookies;
use Auth;
use App\User;

class Access
{
    protected $auth;
    protected $token;

    public function __construct()
    {   
        $this->token = Cookie::get('token') ?? '';
        $this->auth = User::where('api_token', $this->token)->first();
    }

    public function handle($request, Closure $next, $role)
    {   
        if($this->auth) {
            switch($role){
                case 'admin':
                    if(!$this->auth->is_admin) {
                        return new RedirectResponse(url('/'));
                    }
                break;
                case 'promocoder':
                    if(!$this->auth->is_admin && !$this->auth->is_promocoder) {
                        return new RedirectResponse(url('/'));
                    }
                break;
                case 'moder':
                    if(!$this->auth->is_admin && !$this->auth->is_moder) {
                        return new RedirectResponse(url('/'));
                    }
                break;
                default:
                    return new RedirectResponse(url('/'));
                break;
            }
            return $next($request);
        }
        return new RedirectResponse(url('/'));
    }
}
