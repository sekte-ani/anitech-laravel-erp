<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'asc')->paginate(10);

        return view('users.index', compact([
            'users'
        ]));
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

        return view('users.show', compact([
            'user',
        ]));
    }

    public function create()
    {
        $role = Role::all();

        return view('users.create', compact([
            'role',
        ]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
