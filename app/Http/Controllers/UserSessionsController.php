<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserSessions;

class UserSessionsController extends Controller
{
    public function create(Request $r)
    {
        $user = User::query()->find($r->id);

        $session = UserSessions::query()->orderBy('id', 'desc')->where('id', $r->id)->first();
        if ($session != null && $session->type != $r->type) {
            UserSessions::query()->create([
                "type" => $r->type,
                "user_id" => $user->id,
                "balance" => $user->balance,
                "ip" => $r->ip
            ]);
        } else if ($session == null) {
            UserSessions::query()->create([
                "type" => $r->type,
                "user_id" => $user->id,
                "balance" => $user->balance,
                "ip" => $r->ip
            ]);
        }
        

        return "OK";
    }

    public function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
