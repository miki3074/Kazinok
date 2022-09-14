<?php

namespace App\GraphQL\Helpers\Game;

use Illuminate\Support\Str;

class GenerateHash
{
    public static function generate($num = NULL)
    {
        $salt1 = Str::random(16);
        $random = $num ?? mt_rand(0, 999999);
        $salt2 = Str::random(12);
        $string = $salt1 . '|' . $random . '|' . $salt2;
        $hash = hash('sha512', $string);
        $hid = implode("-", str_split(hash('sha1', $random), 4));

        return [
            'salt1' => $salt1,
            'random' => $random,
            'salt2' => $salt2,
            'string' => $string,
            'hash' => $hash,
            'hid' => $hid
        ];
    }
}
