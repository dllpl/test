<?php

namespace App\Helpers;

class Role
{
    public static function isSuperUser(): bool
    {
        if(isset(auth()?->user()?->roles[0])) {
            return auth()->user()->roles[0]->name === 'super-user';
        } else {
            return false;
        }
    }
}