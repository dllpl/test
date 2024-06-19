<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JustCommunication\TinkoffAcquiringAPIClient\API\InitRequest;
use JustCommunication\TinkoffAcquiringAPIClient\Exception\TinkoffAPIException;
use JustCommunication\TinkoffAcquiringAPIClient\TinkoffAcquiringAPIClient;

class TbankPayController extends Controller
{
    public function callback(Request $request)
    {
        \Log::info('TbankPayController callback', $request->all());
    }
}
