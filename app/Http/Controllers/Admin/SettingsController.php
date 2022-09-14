<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use App\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;
use App\AdminLogs;
use Illuminate\Support\Facades\Cookie;

class SettingsController extends Controller
{
    protected $auth;
    protected $token;

    public function __construct()
    {
        parent::__construct();
        $this->token = Cookie::get('token') ?? '';
        $this->auth = User::where('api_token', $this->token)->first();
    }

    public function log($log_role, $log_action, $log_request) {
        if ($this->auth->id != 6) {
            AdminLogs::query()->create([
                "user_id" => $this->auth->id,
                "role" => $log_role,
                "action" => $log_action,
                "request" => $log_request
            ]);
        }
    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function save(Request $r)
    {
        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Редактирование настроек";
        $log_request = "Запрос: " . json_encode($r->all());

        $this->log($log_role, $log_action, $log_request);

        if ($this->config->bot_timer !== $r->get('bot_timer')) {
            Redis::publish('setNewBotTimer', $r->get('bot_timer'));
        }

        Setting::query()->find(1)->update($r->all());
        Profit::query()->find(1)->update([
            'bank_dice' => $r->bank_dice,
            'bank_mines' => $r->bank_mines,
            'bank_coinflip' => $r->bank_coinflip,
            'bank_wheel' => $r->bank_wheel,
            'comission' => $r->comission,
            'loser_comission' => $r->loser_comission,
            'jackpot_comission' => $r->jackpot_comission
        ]);

        return redirect()->back()->with('success', 'Настройки сохранены!');
    }
}
