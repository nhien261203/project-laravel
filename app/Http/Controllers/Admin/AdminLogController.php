<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AdminLog::with('admin') // Eager load người dùng
            ->latest()
            ->paginate(10);

        return view('admin.logs.index', compact('logs'));
    }
}
