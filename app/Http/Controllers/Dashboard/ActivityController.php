<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->filled('user'), fn ($q) => $q->where('user_id', $request->user))
            ->when($request->filled('role'), fn ($q) => $q->whereHas('user', fn ($u) => $u->where('role', $request->role)))
            ->latest('created_at')
            ->paginate(30)->withQueryString();

        return view('dashboard.activity.index', ['logs' => $logs, 'users' => User::orderBy('name')->get()]);
    }
}
