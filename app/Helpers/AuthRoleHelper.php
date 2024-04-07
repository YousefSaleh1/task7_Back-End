<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRoleHelper
{
    public static function isOwner()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);

            return $user && $user->hasRole('owner');
        }
        return false;
    }

    public static function isNotOwner()
    {
        return !self::isOwner();
    }

    public static function isAdmin()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);

            return $user && $user->hasRole('admin');
        }
        return false;
    }

    public static function isNotAdmin()
    {
        return !self::isOwner();
    }

    public static function isCustomer()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);

            return $user && $user->hasRole('customer');
        }
        return false;
    }

    public static function isNotCustomer()
    {
        return !self::isOwner();
    }
}
