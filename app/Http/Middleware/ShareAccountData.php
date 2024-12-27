<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Account;

class ShareAccountData
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $accounts = Account::where('user_id', auth()->id())->get();
            view()->share('accounts', $accounts); // Делает переменную доступной во всех представлениях
        }

        return $next($request);
    }
}

