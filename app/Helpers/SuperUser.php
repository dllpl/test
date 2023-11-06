<?php

namespace App\Helpers;

class SuperUser
{
    public static function status()
    {
        return \DB::table('request_to_super')
            ->where('user_id', auth()->user()->id)
            ->select('status')->first()?->status ?? null;
    }
}