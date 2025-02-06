<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('id', 'asc')->paginate(10);
        $roles = Role::all();

        return view('content.erp.erp-operational-employee', compact([
            'users',
            'roles'
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
        // $role = Role::all();

        // return view('users.create', compact([
        //     'role',
        // ]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'email' => 'required|email|string|unique:users',
            'role_id' => 'required|exists:roles,id',
            'images' => 'nullable|image|file|max:5120|mimes:jpeg,png,jpg',
            'phone' => 'nullable',
        ]);
        $defaultPassword = 'anitech123';
        $validatedData['password'] = Hash::make($defaultPassword);

        if($request->file('images')){
            $validatedData['images'] = $request->file('images')->store('profile-images');
        }

        $user = User::create($validatedData);

        $user->roles()->attach($request->role_id);

        return redirect()->route('emp')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required',
            'images' => 'nullable|image|file|max:5120|mimes:jpeg,png,jpg',
            'phone' => 'nullable|string',
        ]);
        $validatedData['password'] = Hash::make($request->password);
    }

    public function destroy($id)
    {
        //
    }

    public function checkSlugName(Request $request)
    {
        $slug = Str::slug($request->name);
        return response()->json(['slug' => $slug]);
    }
}
