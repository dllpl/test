<?php

namespace App\Http\Controllers\Web\Public\Account;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class CertController extends AccountBaseController
{
    public function index()
    {
        // Meta Tags
        MetaTag::set('title', 'Сертификация');

        return appView('account.cert');
    }
}