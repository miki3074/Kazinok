<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function login(Request $r)
    {
        $username = $r->get('username');
        $password = $r->get('password');

        $admin = Admin::query()->where([['username', $username], ['password', hash('sha256', $password)]])->first();

        if (!$admin) {
            return redirect()->back();
        }

        Auth::login($admin, true);

        return redirect('/admin');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/admin');
    }
}
