<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class BotsController extends Controller
{
    public function index()
    {
        return view('admin.bots.index');
    }

    public function create()
    {
        return view('admin.bots.create');
    }

    public function createPost(Request $request)
    {
        if (User::query()->where('username', $request['username'])->first()) {
            return redirect()->back()->with('error', 'Логин занят');
        }

        User::query()->create([
            'username' => $request['username'],
            'password' => hash('sha256', mt_rand(0,255555)),
            'is_vk' => 0,
            'is_bot' => 1
        ]);

        return redirect('/admin/bots')->with('success', 'Бот создан');
    }

    public function edit($id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        return view('admin.bots.edit', compact('user'));
    }

    public function editPost($id, Request $r)
    {
        User::query()->find($id)->update($r->all());

        return redirect('/admin/bots/edit/' . $id)->with('success', 'Бот обновлен!');
    }

    public function delete($id)
    {
        User::query()->find($id)->delete();

        return redirect()->back()->with('success', 'Бот удален!');
    }
}
