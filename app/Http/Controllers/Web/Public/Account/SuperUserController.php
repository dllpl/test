<?php

namespace App\Http\Controllers\Web\Public\Account;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SuperUserController extends BaseController
{
    public function sendRequest(Request $request)
    {
        $res = \DB::table('request_to_super')
            ->insert([
                'user_id' => $request->user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return response()->json(['status' => true, 'msg' => "Ваша заявка принята и находится в обработке"]);
    }

    public function getStatus(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => \DB::table('request_to_super as request')
                    ->where('user_id', $request->user()->id)
                    ->where('status', 1)
                    ->leftJoin('request_to_super_status_list as status', 'status.status_id', '=', 'request.stats')
                    ->select('request.status', 'status.name', 'status.desc')->first()?->status ?? null
        ]);
    }
}
