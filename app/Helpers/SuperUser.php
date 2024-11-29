<?php

namespace App\Helpers;

class SuperUser
{
    public static function status()
    {
        return \DB::table('request_to_super as request')
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->leftJoin('request_to_super_status_list as status', 'status.status_id', '=', 'request.status')
            ->select('request.status', 'status.name', 'status.desc')->first()?->status ?? null;
    }
}
