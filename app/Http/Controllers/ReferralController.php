<?php

namespace App\Http\Controllers;

use App\ReferralPayment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReferralController extends Controller
{
    public function __construct() {
        parent::__construct();

        if($this->auth && $this->auth->ref_perc > 0) {
            $this->config->ref_perc = $this->auth->ref_perc;
        }
    }

    public function getReferrals(Request $r)
    {
        $users = [];
        $ref_perc = $this->config->ref_perc;
        //if ($r->user()->id == 20 || $r->user()->id == 29544) {
        //    $ref_perc = 5;
        //}
        //if ($r->user()->id == 29544) {
        //    $ref_perc = 5;
        //}
        foreach (User::query()->where('referral_use', $r->user()->id)->get() as $user) {
            $users[] = [
                $user->id, $user->username, $user->created_at->format('d.m.Y H:i:s'), $user->updated_at->format('d.m.Y H:i:s'), round(ReferralPayment::query()->where([['user_id', $user->id], ['referral_id', $r->user()->id]])->sum('sum') * ($ref_perc / 100), 2)
            ];
        }

        return [
            'data' => $users
        ];
    }

    public function getGraph(Request $r)
    {
        $start = $r->get('start');
        $end = $r->get('end');
        $chart = [];

        $period = $this->createDateRangeArray($start, $end);        

        foreach ($period as $key) {
            $date = Carbon::createFromFormat('d.m.Y', $key);

            $ref_perc = $this->config->ref_perc;

            $ref_shave = 7;
            if($r->user()->ref_perc == 11) {
                $ref_shave = 7.5;
            } else if($r->user()->ref_perc == 12) {
                $ref_shave = 8;
            } else if($r->user()->ref_perc == 13) {
                $ref_shave = 8.5;
            } else if($r->user()->ref_perc == 14) {
                $ref_shave = 9;
            } else if($r->user()->ref_perc == 15) {
                $ref_shave = 9.5;
            }

            $chart[] = [
                'x' => $key,
                'y' => round((ReferralPayment::query()->whereDate('created_at', $date->format('Y-m-d'))->where([['referral_id', $r->user()->id], ['shave', 0]])->sum('sum') * ($ref_perc / 100)) + (ReferralPayment::query()->whereDate('created_at', $date->format('Y-m-d'))->where([['referral_id', $r->user()->id], ['shave', 1]])->sum('sum') * ($ref_shave / 100)), 2)
            ];
        }

        return [
            'er' => round((ReferralPayment::query()->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where([['referral_id', $r->user()->id], ['shave', 0]])->sum('sum') * ($ref_perc / 100)) + (ReferralPayment::query()->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where([['referral_id', $r->user()->id], ['shave', 1]])->sum('sum') * ($ref_shave / 100)), 2),
            'chart' => $chart
        ];
    }

    public function getInfo(Request $r)
    {
        $ref_shave = 7;

        if($r->user()->ref_perc == 11) {
            $ref_shave = 7.5;
        } else if($r->user()->ref_perc == 12) {
            $ref_shave = 8;
        } else if($r->user()->ref_perc == 13) {
            $ref_shave = 8.5;
        } else if($r->user()->ref_perc == 14) {
            $ref_shave = 9;
        } else if($r->user()->ref_perc == 15) {
            $ref_shave = 9.5;
        }

        return [
            'ref_percent' => $this->config->ref_perc,
            'referrals' => User::query()->where('referral_use', $r->user()->id)->count('id'),
            'sum' => round((ReferralPayment::query()->where([['referral_id', $r->user()->id], ['shave', 0]])->sum('sum') * ($this->config->ref_perc / 100)) + (ReferralPayment::query()->where([['referral_id', $r->user()->id], ['shave', 1]])->sum('sum') * ($ref_shave / 100)), 2)
        ];
    }

    public function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('d.m.Y',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('d.m.Y',$iDateFrom));
            }
        }
        return $aryRange;
    }
}
