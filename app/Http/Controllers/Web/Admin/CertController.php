<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Controllers\Web\Public\Auth\Traits\VerificationTrait;

class CertController extends PanelController
{
    use VerificationTrait;

    public function setup()
    {

    }
}