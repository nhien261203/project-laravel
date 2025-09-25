<?php

namespace App\Helpers;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class AdminLogHelper
{
    public static function log($action, $description = null)
    {
        if (Auth::check() && Auth::user()) {
            $user = Auth::user();

            AdminLog::create([
                'admin_id'   => Auth::id(),
                'causer_id'   => $user->id,
                'causer_type' => get_class($user),
                'action'     => $action,
                'description'=> $description,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
            ]);
        }
    }
}
