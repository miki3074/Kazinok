<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\ReferralPayment;
use App\Setting;
use App\TopRefovods;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;


class VkController extends Controller
{
    public function handle(Request $r)
    {
        session_start();

        if (!empty($r->get('code'))) {
            $params = [
                'client_id' => \config('services.vk.id'),
                'client_secret' => \config('services.vk.secret'),
                'code' => $r->get('code'),
                'redirect_uri' => \config('services.vk.uri')
            ];

            $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . http_build_query($params)), true);

            if (isset($token['access_token'])) {
                $params = [
                    'uids' => $token['user_id'],
                    'fields' => 'uid,first_name,last_name,photo_max',
                    'access_token' => $token['access_token'],
                    'lang' => 'ru',
                    'v' => '5.103'
                ];

                $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . http_build_query($params)), true);

                if (isset($userInfo['response'][0]['id'])) {
                    $userInfo = $userInfo['response'][0];

                    if (isset($_SESSION['auth_id'])) {
                        $userId = $_SESSION['auth_id'];
                        unset($_SESSION['auth_id']);

                        $user = User::query()->find($userId);
                        $linkUserVK = User::query()->where('vk_id', $userInfo['id'])->first();
                        if ($user) {
                            if (!$linkUserVK) {

                                $settings = Setting::query()->find('1')->first();

                                $balance = $user->balance + $settings->connect_bonus;

                                $user->update([
                                    'is_vk' => 1,
                                    'vk_id' => $userInfo['id'],
                                    'vk_username' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                                    'used_ip' => $this->getIp(),
                                    'balance' => $balance
                                ]);
                            } else {
                                throw new \Exception('Пользователь с таким VK ID уже существует');
                            }
                        }
                    } else {
                        $user = User::query()->where([['vk_id', $userInfo['id']]])->first();

                        if ($user) {
                            $token = Str::random(60);

                            $user->update([
                                'vk_username' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                                'used_ip' => $this->getIp(),
                                'api_token' => $token
                            ]);
                        } else {

                            //reg

                            $username = $userInfo['id'];
                            $settings = Setting::query()->find('1')->first();

                            if (User::query()->where('username', 'vk'.$username)->first()) {
                                throw new \Exception('Пользователь уже существует');
                            }

                            $ref = null;
                            $httpref = null;

                            if(isset($_SESSION['httpref'])) {
                                $httpref = $_SESSION['httpref'];
                            }

                            $buks = ['1','https://seo-fast.ru/', 'https://socpublic.com/', 'https://toloka.yandex.ru/', 'https://taskpay.ru/', 'https://qcomment.ru/', 'https://cashbox.ru/', 'https://profitcentr.com/', 'https://vktarget.ru/', 'https://profittask.com/', 'https://wmrfast.com/', 'http://www.wmmail.ru/', 'https://surfearner.com/', 'https://lamatop.com/', 'https://www.ipweb.ru/', 'https://aviso.bz/'];

                            if (isset($_SESSION['ref'])) {
                                $ref = $_SESSION['ref'];

                                if (!User::query()->find($ref)) {
                                    $ref = null;
                                } else {
                                    $refer = User::query()->find($ref);
                                    if ($refer->is_ref_bonus === 1) {
                                        if (!User::query()->where('created_ip', $this->getIp())->orWhere('used_ip', $this->getIp())->first()) {
                                            if ($this->getIp() !== $refer->created_ip && $this->getIp() !== $refer->used_ip) {
                                                if(!array_search($httpref, $buks)) {
                                                    $refbalance = $refer->balance + 5;
                                                    $wager = $refer->wager + 5;
                                                    $refer->wager = $wager;
                                                    $refer->balance = $refbalance;                                            
                                                    $refer->ref_bonus_cnt += 1;
                                                }                                          
                                            }
                                        }
                                    }
                                    $refer->link_reg += 1;
                                    $refer->save();

                                    $top = TopRefovods::query()->where('user_id', $ref)->first();
                                    if (!$top) {
                                        TopRefovods::create([
                                            'user_id' => $refer->id,
                                            'username' => $refer->username,
                                            'ref_cnt' => 1,
                                            'sum' => 5
                                        ]);
                                    } else {
                                        $top->update([
                                            'ref_cnt' => $top->ref_cnt + 1,
                                            'sum' => $top->sum + 5
                                        ]);
                                    }
                                }
                            }

                            $token = Str::random(60);

                            $user = User::query()->create([
                                'username' => 'vk'.$username,
                                //'password' => $args['password'],
                                'api_token' => $token,
                                'referral_use' => $ref,
                                'created_ip' => $this->getIp(),
                                'used_ip' => $this->getIp(),
                                'httpref' => $httpref,
                                'is_vk' => 1,
                                'vk_id' => $userInfo['id'],
                                'vk_username' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                                'vk_only' => 1,
                                'balance' => $settings->connect_bonus
                            ]);

                            return redirect('/auth/callback?token=' . $user->api_token);
                        }
                    }

                    return redirect('/auth/callback?token=' . $user->api_token);
                }
            }
        }
    }
    public function getIp() {
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
