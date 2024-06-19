<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TbankPayController extends Controller
{
    public function callback(Request $request)
    {
        \Log::info('TbankPayController callback', $request->all());
    }
}
