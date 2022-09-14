<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\User;
use App\Slots;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class SlotsController extends Controller
{
    public function __construct() 
    {
		parent::__construct();
    }

    public function index() 
    {
        return view('admin.slots.index');
    }

    public function update() 
    {
        $get = $this->curl("https://int.apiforb2b.com/frontendsrv/apihandler.api?cmd=".json_encode([
            "api" => "ls-games-by-operator-id-get",
            "operator_id" => $this->OPERATOR_ID
        ]));

        $i = 0;
        $list = [];

        Slots::truncate();;

        foreach($get['locator']['groups'] as $i => $info) {
            if(in_array($info['gr_title'], $this->PROVIDERS)) {
                foreach($info['games'] as $games) {
                    Slots::create([
                        'game_id' => $games['gm_bk_id'],
                        'title' => $games['gm_title'],
                        'icon' => 'https://int.apiforb2b.com/game/icons/' . $games['icons'][0]['ic_name']
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Список игр обновлен');
    }

    public function edit($id)
    {
        $game = Slots::query()->find($id);

        if (!$game) {
            return redirect()->back()->with('error', 'Слот не найден!');
        }
        
        return view('admin.slots.edit', compact('game'));
    }

    public function save($id, Request $r)
    {
        $game = Slots::query()->find($id);

        if (!$game) {
            return redirect()->back()->with('error', 'Слот не найден!');
        }

        Slots::query()->where('id', $id)->update($r->all());
        return redirect()->back()->with('success', 'Изменения сохранены!');
    }
}