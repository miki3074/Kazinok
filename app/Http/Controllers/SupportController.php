<?php

namespace App\Http\Controllers;

use App\Support;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    private $month = [
        'января',
        'февраля',
        'мара',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря'
    ];

    public function sendMessage(Request $r)
    {
        $message = strip_tags($r->get('message'));

        $r->validate([
            'message' => 'required|max:255'
        ], [
            'message.required' => 'Введите сообщение',
            'message.max' => 'Максимум 255 символов'
        ]);

        Support::query()->create([
            'user_id' => $r->user()->id,
            'message' => $message
        ]);

        return $this->getMessagesInUser($r->user()->id);
    }

    public function getMessages(Request $r)
    {
        return $this->getMessagesInUser($r->user()->id);
    }

    private function getMessagesInUser($id)
    {
        $supports = Support::query()->where('user_id', $id)->orderBy('created_at', 'ASC')->get();
        $messages = [];

        foreach ($supports as $support) {
            $month = $support->created_at->format('d') . ' ' . $this->month[$support->created_at->format('n') - 1];

            if ($support->is_admin) {
                $username = 'Администратор';
            } else {
                $username = User::query()->find($support->user_id)->username;
            }

            if (isset($messages[$month])) {
                $mes = $messages[$month]['messages'];
                $mes[] = [
                    'message' => $support->message,
                    'is_admin' => $support->is_admin,
                    'time' => $support->created_at->format('H:i:s'),
                    'username' => $username
                ];

                $messages[$month]['messages'] = $mes;
            } else {
                $messages[$month] = [
                    'messages' => [[
                        'message' => $support->message,
                        'is_admin' => $support->is_admin,
                        'time' => $support->created_at->format('H:i:s'),
                        'username' => $username
                    ]]
                ];
            }
        }

        return $messages;
    }
}
