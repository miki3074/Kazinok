<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Promocode;
use App\PromocodeActivation;
use App\ReferralPayment;
use Illuminate\Http\Request;
use App\Wallets;
use App\AdminLogs;
use App\Withdraw;
use App\Payment;
use Illuminate\Support\Facades\Cookie;

class UsersController extends Controller
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

    public function indexTop()
    {
        $top = \App\Top::query()->get();
        return view('admin.users.tops', compact('top'));
    }

    public function makeTop()
    {
        foreach(User::select('id', 'username')->where('id', '>', 0)->get()->chunk(1000) as $chunk) {
            foreach($chunk as $usr) {
                \App\Top::create([
                    'user_id' => $usr['id'],
                    'username' => $usr['username'],
                    'sum' => \App\Payment::query()->where([['fake', 0], ['user_id', $usr['id']]])->sum('sum')
                ]);
            }
        }
        return "OK";
    }

    
    public function index()
    {
        $top =[];
        /*foreach(User::select('id', 'username')->where('id', '>', 0)->get()->chunk(300) as $chunk) {
            foreach($chunk as $usr) {
                $top[] = [
                    'id' => $usr['id'],
                    'username' => $usr['username'],
                    'sum' => \App\Payment::query()->where([['fake', 0], ['user_id', $usr['id']]])->sum('sum')
                ];
            }
        }
*/        return view('admin.users.index', compact('top'));
    }

    public function games($id)
    {
        $user = User::query()->find($id);
        if(!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        return view('admin.users.games', compact('user'));
    }

    public function clearmult($id)
    {
        $user = User::query()->find($id);
        foreach (User::query()->where('created_ip', $user->created_ip)->orWhere('used_ip', $user->used_ip)->orWhere('used_ip', $user->created_ip)->orWhere('created_ip', $user->used_ip)->get() as $m) {
            if($m->id != $user->id) {
                User::query()->find($m->id)->delete();
            }
        }

        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Удаление мульт акков пользователя #" . $id;
        $log_request = "Запрос: " . $id;

        $this->log($log_role, $log_action, $log_request);

        return redirect('/admin/users/edit/' . $id)->with('success', 'Мульт акки успешно удалены!');

    }

    public function edit($id)
    {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        //$pass_mults = [];
        //$wal_sovp = [];
        
        //$wdws = round(Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->orWhere([['user_id', $user->id], ['status', 3]])->sum('sumNoCom'), 2);
        //$pays = round(Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2);
        //$razn = $pays - $wdws;
        //$total = $razn;

        //foreach (User::query()->where('created_ip', $user->created_ip)->orWhere('used_ip', $user->used_ip)->get() as $m) {
        //    if($m->id != $user->id) {
        //        $mults[] = [
        //            'id' => $m->id, 
        //            'username' => $m->username, 
        //            'balance' => $m->balance,
        //            'created_at' => $m->created_at->format('d-m-Y h:i:s'), 
        //            'updated_at' => $m->updated_at->format('d-m-Y h:i:s'), 
        //            'created_ip' => $m->created_ip,
        //            'used_ip' => $m->used_ip
        //        ];
        //        $wdws1 = round(Withdraw::query()->where([['user_id', $m->id], ['status', 1]])->orWhere([['user_id', $user->id], ['status', 3]])->sum('sumNoCom'), 2);
        //        $pays1 = round(Payment::query()->where([['user_id', $m->id], ['status', 1]])->sum('sum'), 2);
        //        $razn1 = $pays1 - $wdws1;
        //        $total += $razn1;
        //    }
        //}
        //if($user->password != '') {
        //    foreach (User::query()->where('password', 'like', '%'.$user->password.'%')->get() as $p) {
        //        if($p->id != $user->id) {
        //            $pass_mults[] = [
        //                'id' => $p->id, 
        //                'username' => $p->username, 
        //                'balance' => $p->balance,
        //                'created_at' => $p->created_at->format('d-m-Y h:i:s'), 
        //                'updated_at' => $p->updated_at->format('d-m-Y h:i:s'), 
        //                'created_ip' => $p->created_ip,
        //                'used_ip' => $p->used_ip
        //            ];
        //        }
        //    }
        //}
        //foreach (\App\Wallets::query()->where('user_id', $user->id)->get() as $w) {
        //    $cnt = Wallets::query()->where([ ['wallet', 'like', '%' . $w->wallet . '%'], ['user_id', '!=', $user->id], ['is_included', 1]])->groupBy('user_id')->get()->count('user_id');
        //    $cnt1 = Withdraw::query()->where([ ['wallet', 'like', '%' . $w->wallet . '%'], ['user_id', '!=', $user->id], ['fake', 0]])->groupBy('user_id')->get()->count('user_id');
        //    $sys = $w->system;
        //    if ($sys == 4 || $sys == 15) {$sys = "QIWI";}
        //    else if ($sys == 9 || $sys == 16) {$sys = "Карты";}
        //    else if ($sys == 1) {$sys = "ЮMoney";}
        //    else if ($sys == 12) {$sys = "FKWallet";}
        //    else if ($sys == 14) {$sys = "Piastrix";}
        //    $wal_sovp[] = [
        //        'id' => $w->id,
        //        'wallet' => $w->wallet,
        //        'system' => $sys,
        //        'cnt' => $cnt1 . '(' . $cnt . ')',
        //        'is_included' => $w->is_included
        //    ];
        //}

        //Заработал на реф.системе
        $ref_perc = $this->config->ref_perc;
        if ($user->ref_perc != 0) {
            $ref_perc = $user->ref_perc;
        }
        $refearn = round(ReferralPayment::query()->where('referral_id', $user->id)->sum('sum') * ($ref_perc / 100) + ($user->ref_bonus_cnt * 5), 2);

        //Пополнил
        $payments = round(\App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2);

        //Вывел
        $withdraws = round(\App\Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->orWhere([['user_id', $user->id], ['status', 3]])->sum('sumNoCom'), 2);

        //Депозиты рефералов
        $refdep = round(ReferralPayment::query()->where('referral_id', $user->id)->sum('sum'), 2);

        //Выводы рефералов
        $refwdw = round(\App\ReferralWithdraw::query()->where('referral_id', $user->id)->sum('sum'), 2);

        //Заработок сайта
        $zarab = round($refdep - $refwdw - ($user->ref_bonus_cnt * 5), 2);

        //Профит дайс
        //$profitdice = round(\App\Game::query()->where([['user_id', $user->id], ['game', 'dice']])->sum('win') - \App\Game::query()->where([['user_id', $user->id], ['game', 'dice']])->sum('bet'), 2);
        $profitdice = 0;
        $profitmine = 0;
        //Профит мины
        //$profitmine = round(\App\Game::query()->where([['user_id', $user->id], ['game', 'mines']])->sum('win') - \App\Game::query()->where([['user_id', $user->id], ['game', 'mines']])->sum('bet'), 2);

        
        return view('admin.users.edit', compact('user', 'refearn', 'payments', 'withdraws', 'refdep', 'refwdw', 'zarab', 'profitdice', 'profitmine'));
    }

    public function editWallet($id)
    {
        $wallet = Wallets::query()->find($id);

        if (!$wallet) {
            return redirect()->back()->with('error', 'Кошелек не найден!');
        }

        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Добавление в исключение кошелька " . $wallet->wallet;
        $log_request = "Запрос: " . $wallet->wallet . ' ' . $id;

        $this->log($log_role, $log_action, $log_request);

        Wallets::query()->find($id)->update(['is_included' => 0]);

        return redirect('/admin/users/edit/' . $wallet->user_id)->with('success', 'Данные успешно обновлены!');
    }

    public function editPost($id, Request $r)
    {

        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}

        $log_action = "Редактирование пользователя #" . $id;

        $user = User::query()->find($id);

        if (isset($r->username) && $user->username != $r->username) {$log_action = "Смена ника пользователя #" . $id . " на " . $r->username;}
        else if (isset($r->password) && $user->password != $r->password) {$log_action = "Смена пароля пользователя #" . $id . " на " . $r->password;}
        else if (isset($r->balance) && $user->balance != $r->balance) {$log_action = "Смена баланса пользователя #" . $id . " на " . $r->balance;}
        else if (isset($r->is_youtuber) && $user->is_youtuber != $r->is_youtuber) {$log_action = "Установлен статус стримера пользователю #" . $id;}
        else if (isset($r->is_moder) && $user->is_moder != $r->is_moder) {$log_action = "Установлен статус модера пользователю #" . $id;}
        else if (isset($r->is_promocoder) && $user->is_promocoder != $r->is_promocoder) {$log_action = "Установлен статус промокодера пользователю #" . $id;}
        else if (isset($r->is_admin) && $user->is_admin != $r->is_admin) {$log_action = "Установлен статус админ пользователю #" . $id;}
        else if (isset($r->ban) && $user->ban != $r->ban) {$log_action = "Выдан бан пользователю #" . $id;}
        else if (isset($r->ref_perc) && $user->ref_perc != $r->ref_perc) {$log_action = "Установлена процент " . $r->ref_perc . "% рефералки пользователю #" . $id;}
        else if (isset($r->is_ref_bonus) && $user->is_ref_bonus != $r->is_ref_bonus) {$log_action = "Отключение\включение бонуса за рефа пользователю #" . $id;}

        $log_request = "Запрос: " . json_encode($r->all());

        $this->log($log_role, $log_action, $log_request);

        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        //if($user->created_ip == $r['created_at']) {

        //}

        // if ($user->password !== $r->get('password')) {
        //     $user->update([
        //         'password' => hash('sha256', $r->get('password'))
        //     ]);
        // }
        if($this->auth->is_moder) {
            $r->is_admin = $user->is_admin;
            $r->is_moder = $user->is_moder;
            $r->is_youtuber = $user->is_youtuber;
        }

        User::query()->find($id)->update($r->all());

        return redirect('/admin/users/edit/' . $id)->with('success', 'Данные пользователя обновлены!');
    }

    public function delete($id)
    {
        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Удаление пользователя #" . $id;
        $log_request = "Запрос: " . $id;

        $this->log($log_role, $log_action, $log_request);

        User::query()->find($id)->delete();

        return redirect('/admin/users')->with('success', 'Пользователь удален');
    }

    public function promocodes($id)
    {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        $promocodes = [];

        foreach (PromocodeActivation::query()->where('user_id', $user->id)->get() as $p) {
            $promocodes[] = [
                'id' => $p->id,
                'name' => Promocode::query()->find($p->promo_id)->name,
                'type' => $p->type == 'balance' ? 'Баланс' : 'Пополнение',
                'date' => $p->created_at->format('d.m.Y H:i:s')
            ];
        }

        return view('admin.users.promocodes', compact('user', 'promocodes'));
    }

    public function tables($id)
    {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }
        $mults = [];

        foreach (User::query()->where('created_ip', $user->created_ip)->orWhere('used_ip', $user->used_ip)->get() as $m) {
            if($m->id != $user->id) {
                $mults[] = [
                    'id' => $m->id, 
                    'username' => $m->username, 
                    'balance' => $m->balance,
                    'created_at' => $m->created_at->format('d-m-Y h:i:s'), 
                    'updated_at' => $m->updated_at->format('d-m-Y h:i:s'), 
                    'created_ip' => $m->created_ip,
                    'used_ip' => $m->used_ip
                ];
            }
        }
        

        return view('admin.users.tables', compact('user', 'mults'));
    }

    public function tablesRef($id)
    {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }
        $refs = [];

        foreach (User::query()->where('referral_use', $user->id)->get() as $u) {
            $refs[] = [
                'id' => $u->id, 
                'username' => $u->username, 
                'created_at' => $u->created_at->format('d-m-Y h:i:s'), 
                'updated_at' => $u->updated_at->format('d-m-Y h:i:s'), 
                'profit' => ReferralPayment::query()->where([['user_id', $u->id], ['referral_id', $user->id]])->sum('sum') * ($this->config->ref_perc / 100)
            ];
        }
        

        return view('admin.users.tablesref', compact('user', 'refs'));
    }

    public function createFake($type, $id) {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        return view('admin.users.create' . $type, compact('user'));
    }

    public function addFake($type, $id, Request $r) {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        if($type == 'Payout') {
            $system = $r->system;
            $wallet = $r->wallet;
            $amount = $r->amount;
            $status = $r->status;
            
            if(!$system) {
                return redirect()->back()->with('error', 'Выберите платежную систему');
            }
    
            if(!$wallet) {
                return redirect()->back()->with('error', 'Введите кошелек корректно');
            }
    
            if(!$amount || !is_numeric($amount) || $amount < 1) {
                return redirect()->back()->with('error', 'Введите сумму корректно');
            }
    
            if(!$status) {
                return redirect()->back()->with('error', 'Выберите статус выплаты');
            }
    
            \App\Withdraw::query()->create([
                'user_id' => $user->id,
                'sum' => $amount,
                'wallet' => $wallet,
                'system' => $system,
                'status' => $status,
                'fake' => 1
            ]);

            if ($this->auth->is_admin == 1) {$log_role = "admin";}
            if ($this->auth->is_moder == 1) {$log_role = "moder";}
            if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
            $log_action = "Фейк вывод пользователю #" . $user->id . "Сумма: " . $amount . "Статус: " . $status;
            $log_request = "Запрос: " . $type . $id . " рек: " . json_encode($r);

            $this->log($log_role, $log_action, $log_request);
        }

        if($type == 'Pay') {
            $amount = $r->amount;
            $add = $r->add;
            
            if(!$amount || !is_numeric($amount) || $amount < 1) {
                return redirect()->back()->with('error', 'Введите сумму корректно');
            }
    
            if(!$add) {
                return redirect()->back()->with('error', 'Заполните все поля');
            }

            $da = "Нет";
            
            if($add == 'y') {
                $user->balance += $amount;
                $user->save();
                $da = "Да";
            }

            \App\Payment::query()->create([
                'user_id' => $user->id,
                'sum' => $amount,
                'status' => 1,
                'fake' => 1
            ]);

            if ($this->auth->is_admin == 1) {$log_role = "admin";}
            if ($this->auth->is_moder == 1) {$log_role = "moder";}
            if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
            $log_action = "Пополнение пользователю #" . $user->id . "Сумма: " . $amount . " Зачислен?: " . $da;
            $log_request = "Запрос: " . $type . $id . " рек: " . json_encode($r);

            $this->log($log_role, $log_action, $log_request);

        }
        return redirect()->back()->with('success', $type == 'Payout' ? 'Выплата успешно добавлена' : 'Пополнение успешно добавлено');
    }
}
