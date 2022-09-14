<?php

namespace App\Http\Controllers\Admin;

use App\Game;
use App\User;
use App\Payment;
use App\Profit;
use App\Promocode;
use App\Coin;
use App\TopRefovods;
use App\WheelBets;
use App\Withdraw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class IndexController extends Controller
{
    public function index()
    {
        $oldprofitDice = Profit::query()->find(1)->old_earn_dice;
        $oldprofitMines = Profit::query()->find(1)->old_earn_mines;
        $oldprofitWheel = Profit::query()->find(1)->old_earn_wheel;
        $oldprofitCoinflip = Profit::query()->find(1)->old_earn_coinflip;
        $oldprofitSlots = Profit::query()->find(1)->old_earn_slots;
        $allprofitDice = Profit::query()->find(1)->all_earn_dice;
        $allprofitMines = Profit::query()->find(1)->all_earn_mines;
        $allprofitWheel = Profit::query()->find(1)->all_earn_wheel;
        $allprofitCoinflip = Profit::query()->find(1)->all_earn_coinflip;
        $allprofitSlots = Profit::query()->find(1)->all_earn_slots;
        $profitSlots = Profit::query()->find(1)->earn_slots;
        $profitDice = Profit::query()->find(1)->earn_dice;
        $profitMines = Profit::query()->find(1)->earn_mines;
        $profitWheel = Profit::query()->find(1)->earn_wheel;
        $profitCoinflip = Profit::query()->find(1)->earn_coinflip;
        $profitStairs = Profit::query()->find(1)->earn_stairs;
        $oldprofitStairs = Profit::query()->find(1)->all_earn_stairs;
        $allprofitStairs = Profit::query()->find(1)->old_earn_stairs;
        
        return view('admin.index', compact('profitDice', 'profitMines', 'profitCoinflip', 'profitWheel', 'oldprofitDice', 'oldprofitMines', 'oldprofitCoinflip', 'oldprofitWheel', 'profitSlots', 'oldprofitSlots', 'allprofitDice', 'allprofitMines', 'allprofitCoinflip', 'allprofitWheel', 'allprofitSlots', 'profitStairs', 'allprofitStairs', 'oldprofitStairs'));
    }

    public function getFromDate(Request $r) {
        $wdws = Withdraw::query()->where([['status', 1], ['fake', 0], ['created_at', '>', $r->startDate], ['created_at', '<', $r->endDate]])->orWhere([['status', 3], ['fake', 0], ['created_at', '>', $r->startDate], ['created_at', '<', $r->endDate]])->get();
        return view('admin.withdraws.datewdw', compact('wdws'));
    }

    public function indexAdminLogs()
    {
        return view('admin.logs.index');
    }

    public function refovods()
    {
        return view('admin.refovods.index');
    }

    public function updateW() {
        $withdraws = Withdraw::query()->get();

        foreach ($withdraws as $withdraw) {
            if ($withdraw->username == null) {
                $user = User::query()->find($withdraw->user_id);
                //dd($user);
                $withdraw->update([
                    'username' => $user['username']
                ]);
            }            
        }
        return "OK";
    }

    public function load(Request $r) 
    {
        switch($r->type) {
            case 'withdraws':
                return datatables(Withdraw::query())->toJson();
            break;
            case 'users':
                return datatables(User::query())->toJson();
            break;
            case 'promocodes':
                return datatables(Promocode::query())->toJson();
            break;
            case 'dice':
                return datatables(Game::query()->where([['user_id', $r->user_id], ['game', 'dice']]))->toJson();
            break;
            case 'mines':
                return datatables(Game::query()->where([['user_id', $r->user_id], ['game', 'mines']]))->toJson();
            break;
            case 'stairs':
                return datatables(Game::query()->where([['user_id', $r->user_id], ['game', 'stairs']]))->toJson();
            break;
            case 'coin':
                return datatables(Coin::query()->where([['user_id', $r->user_id], ['status', '1']]))->toJson();
            break;
            case 'x50':
                return datatables(WheelBets::query()->where('user_id', $r->user_id))->toJson();
            break;
            case 'payments':
                return datatables(Payment::query()->where('status', '1'))->toJson();
            break;
            case 'refovod':
                return datatables(TopRefovods::query())->toJson();
            break;
            case 'promo':
                return datatables(Promocode::query())->toJson();
            break;
            case 'refs':
                $refs = [];
                foreach (User::query()->where('referral_use', $r->user_id)->get() as $u) {
                    $refs[] = [
                        'id' => $u->id, 
                        'username' => $u->username, 
                        'created_at' => $u->created_at->format('d-m-Y h:i:s'), 
                        'updated_at' => $u->updated_at->format('d-m-Y h:i:s'), 
                        'profit' => \App\ReferralPayment::query()->where([['user_id', $u->id], ['referral_id', $r->user_id]])->sum('sum') * ($this->config->ref_perc / 100)
                    ];
                }
                return datatables($refs)->toJson();
            break;
        }
    }

    public function getUserByMonth() {
        $chart = User::select(DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), DB::raw('count(*) as count'))
            ->where('is_bot', 0)
            ->whereMonth('created_at', '=', date('m'))
            ->groupBy('date')
            ->get();
        
        return $chart;
    }
    
    public function getDepsByMonth() {
        $chart = Payment::where('status', 1)->where('fake', 0)->select(DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), DB::raw('SUM(sum) as sum'))
            ->whereMonth('created_at', '=', date('m'))
            ->groupBy('date')
            ->get();
        
        return $chart;
    }
    public function getMerchant() {
        $shop_id = $this->config->xmpay_id;
        $api_key = $this->config->xmpay_secret;

        $url = "https://xmpay.one/api/getBalance";

        $data = http_build_query([
            'merchant_id' => $shop_id,
            'secret_key' => $api_key
        ]);
        
        $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($curl);
		$content = json_decode($response, true);
        
		curl_close($curl);

        return isset($content['data']) ? str_replace(".00", "", $content['data']['balance']) : $content['error'];
    }

    public function getMerchantFK() {
        $shop_id = $this->config->wallet_id;
        $api_key = $this->config->wallet_secret;

        $data = array(
            'wallet_id'=>$shop_id,
            'sign'=>md5($shop_id.$api_key),
            'action'=>'get_balance',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = trim(curl_exec($ch));
        $c_errors = curl_error($ch);
        curl_close($ch);

        $content = json_decode($result, true);

        //dd($content);

        return isset($content['status']) ? str_replace(".00", "", $content['data']['RUB']) : "Ошибка";
    }

    public function getBalance() {
        $sum = 0;
        foreach(User::select('id', 'balance')->where('id', '>', 0)->get() as $user) {
            $pay = Payment::query()->where('user_id', $user['id'])->first();
            if ($pay) {
                $sum = $sum + $user['balance'];
            }
        }
        return $sum;
    }

    public function getMerchantGet() {
            $shop_id = $this->config->gp_id;
            $api_key = $this->config->gp_api;

            //$sign = md5($api_key . $shop_id . $api_key);

            $data = http_build_query([
                'secret' => $api_key,
                'wallet' => $shop_id,
            ]);

            $url = "https://getpay.io/api/wallets?" . $data;
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $c_errors = curl_error($curl);

            $response = curl_exec($curl);
            $content = json_decode($response, true);
        
            curl_close($curl);

            return isset($content['wallets'][0]['balance']) ? str_replace(".00", "", $content['wallets'][0]['balance']) : "Ошибка";
    }

    public function getMerchantPias() {
        $shop_id = $this->config->piastrix_shop;
        $shop_secret = $this->config->piastrix_secret;
        $now = date("d-m-Y H:i:s");
        $sign = hash('sha256', $now . ':' . $shop_id . $shop_secret);
        $params = [
            "now" =>  $now,
            "shop_id" => $shop_id,
            "sign" => $sign
        ];
        $url = 'https://core.piastrix.com/shop_balance';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        //dd($result);
                
        curl_close($curl);

            return isset($result['data']['balances'][3]['available']) ? str_replace(".00", "", $result['data']['balances'][3]['available']) : "Ошибка";
    }

    public function getMerchantRUB() {
            $shop_id = $this->config->rubpay_id;
            $api_key = $this->config->rubpay_api;

            $sign = md5($api_key . $shop_id . $api_key);

            $data = http_build_query([
                'project_id' => $shop_id,
                'sign' => $sign,
            ]);

            $url = "https://rubpay.ru/api/project/balance?" . $data;
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $c_errors = curl_error($curl);

            $response = curl_exec($curl);
            $content = json_decode($response, true);
        
            curl_close($curl);

            return isset($content['balance']['amount']) ? str_replace(".00", "", $content['balance']['amount']) : "Ошибка";
    }

    public function getVK(Request $r)
    {
        $id = $r->vk_id;

        $info = file_get_contents("https://vk.com/foaf.php?id={$id}");
        $data = preg_match('|ya:created dc:date="(.*?)"|si', $info, $arr);

        return date("d.m.Y H:i:s", strtotime($arr[1]));
    }

    public function getCountry(Request $r)
    {
        $ip = $r->user_ip;

        $curl = curl_init("http://ip-api.com/json/{$ip}?lang=ru");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
        curl_close($curl);
         
        $content = json_decode($response, true);

        return $content['status'] == 'fail' ? $content['message'] : $content['city'];
    }
}
