<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index()
    {
        $role_user = RoleUser::orderBy('id', 'asc')->paginate(10);

        return view('content.general.general-organization-struc', compact([
            'role_user',
        ]));
    }
}
