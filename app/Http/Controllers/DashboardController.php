<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::with('roles')->find(Auth::id());
        $roles = Role::all();

        return view('content.dashboard', compact([
            'user',
            'roles',
        ]));
    }
}
