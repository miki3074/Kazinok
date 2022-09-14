<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support;
use App\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $supports = Support::query()->groupBy('user_id')->orderBy('created_at', 'DESC')->get();

        return view('admin.support.index', compact('supports'));
    }

    public function delete($id)
    {
        $support = Support::query()->find($id);

        if (!$support) {
            return redirect()->back();
        }

        $support->delete();
        return redirect('/admin/support');
    }

    public function chat($id)
    {
        $support = Support::query()->find($id);

        if (!$support) {
            return redirect()->back();
        }

        $messages = $this->getMessagesInUser($support->user_id);
        $user = User::query()->find($support->user_id);

        return view('admin.support.chat', compact('messages', 'user', 'id'));
    }

    public function sendMessage($id, Request $r)
    {
        if (strlen($r->get('message')) < 0) {
            return redirect()->back();
        }

        Support::query()->create([
            'user_id' => $id,
            'is_admin' => 1,
            'message' => $r->get('message')
        ]);

        return redirect()->back();
    }

    private function getMessagesInUser($id)
    {
        $supports = Support::query()->where('user_id', $id)->orderBy('created_at', 'ASC')->get();
        $messages = [];

        foreach ($supports as $support) {
            if ($support->is_admin) {
                $username = 'Администратор';
            } else {
                $username = User::query()->find($support->user_id)->username;
            }

            $messages[] = [
                'id' => $support->id,
                'user_id' => $support->user_id,
                'message' => $support->message,
                'is_admin' => $support->is_admin,
                'time' => $support->created_at->format('d.m.Y H:i:s'),
                'username' => $username
            ];
        }

        return $messages;
    }
}
