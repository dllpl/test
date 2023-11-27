<?php

namespace App\Http\Controllers\Web\Public\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SuperUserController extends Controller
{
    public function sendRequest(Request $request)
    {
        $res = \DB::table('request_to_super')
            ->insert([
                'user_id'=>$request->user()->id,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ]);

        return response()->json(['status'=>true,'msg'=>"Ваша заявка принята и находится в обработке" ]);
    }
}
