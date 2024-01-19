<?php

namespace App\Http\Controllers\Web\Public\Account;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class CertController extends AccountBaseController
{
    public function index()
    {
        // Meta Tags
        MetaTag::set('title', 'Аккредитация');

        return appView('account.cert');
    }
}
