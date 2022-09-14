<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Promocode;
use App\PromocodeActivation;
use Illuminate\Http\Request;
use App\User;
use App\AdminLogs;
use Illuminate\Support\Facades\Cookie;

class PromocodeController extends Controller
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
        $promocodess = \App\Promocode::query()->orderBy('id', 'desc')->get()->chunk(500)->toJson();
        return view('admin.promocodes.index')->with('promocodess', $promocodess);
    }

    public function create()
    {
        return view('admin.promocodes.create');
    }

    public function createPost(Request $request)
    {
        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Создание промо";
        $log_request = "Запрос: " . json_encode($request->all());

        $this->log($log_role, $log_action, $log_request);

        if($request->count == 1) {
            if(Promocode::query()->where('name', $request->name)->first()) return back()->with('error', 'Промокод уже существует');
            Promocode::query()->create([
                'name' => $request->name,
                'sum' => $request->sum,
                'activation' => $request->activation,
                'act' => $request->activation,
                'wager' => $request->wager,
                'type' => $request->type,
                'comment' => $request->comment
            ]);
            return redirect('/admin/promocodes/')->with('promocodes', 'Промокод создан!');
        } else {
            $promocodes = [];
            for($i = 1; $i <= $request->count; $i++) {
                
                $name = strtoupper(bin2hex(random_bytes(4)));
                $promocodes[] = [
                    'name' => $name,
                    'sum' => $request->sum,
                    'activation' => $request->activation,
                    'wager' => $request->wager,
                    'type' => $request->type
                ];
                Promocode::query()->create([
                    'name' => $name,
                    'sum' => $request->sum,
                    'activation' => $request->activation,
                    'act' => $request->activation,
                    'wager' => $request->wager,
                    'type' => $request->type,
                    'comment' => $request->comment
                ]);
            }
        }
        return view('admin.promocodes.create')->with('promocodes', $promocodes);
    }

    public function edit($id)
    {
        $promocode = Promocode::query()->find($id);

        if (!$promocode) {
            return redirect()->back();
        }

        return view('admin.promocodes.edit', compact('promocode'));
    }

    public function editPost($id, Request $r)
    {
        Promocode::query()->find($id)->update($r->all());

        return redirect('/admin/promocodes/edit/' . $id);
    }

    public function delete($id)
    {
        if ($this->auth->is_admin == 1) {$log_role = "admin";}
        if ($this->auth->is_moder == 1) {$log_role = "moder";}
        if ($this->auth->is_promocoder == 1) {$log_role = "promocoder";}
        $log_action = "Удаление промо";
        $log_request = "Запрос: " . $id;

        $this->log($log_role, $log_action, $log_request);

        Promocode::query()->find($id)->delete();
        PromocodeActivation::query()->where('promo_id', $id)->delete();
        
        return redirect()->back()->with('success', 'Промокод удален!');
    }
}
