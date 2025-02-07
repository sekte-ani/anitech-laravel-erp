<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $employees = Employee::with('roles')->orderBy('id', 'asc')->paginate(10);
        $users = User::with('employee')->orderBy('id', 'asc')->paginate(10);
        $roles = Role::where('name', '!=', 'Admin')->get();
        $divisions = Division::all();

        return view('content.erp.erp-operational-employee', compact([
            'employees',
            'users',
            'roles',
            'divisions',
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
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'slug' => 'required|string',
                'email' => 'required|email|string|unique:users',
                'role_id' => 'required|exists:roles,id',
                'division_id' => 'required|exists:divisions,id',
                'images' => 'nullable|image|file|max:5120|mimes:jpeg,png,jpg',
                'phone' => 'nullable',
            ]);

            $defaultPassword = 'anitech123';

            $validatedData['images'] = $request->file('images') 
            ? $request->file('images')->store('profile-images') 
            : null;

            $employee = Employee::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'role_id' => $validatedData['role_id'],
                'division_id' => $validatedData['division_id'],
                'images' => $validatedData['images'],
                'phone' => $validatedData['phone'],
            ]);

            User::create([
                'email' => $validatedData['email'],
                'employee_id' => $employee->id,
                'password' => Hash::make($defaultPassword),
            ]);

            $employee->roles()->attach($request->role_id);
            $employee->divisions()->attach($request->division_id);

            DB::commit();

            return redirect()->route('emp')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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

    public function destroy(string $id)
    {
        $deletedUser = User::find($id);
        $deletedUser->delete();

        return redirect()->back()->with('success', 'Data Karyawan Berhasil Dihapus');
    }

    public function checkSlugName(Request $request)
    {
        $slug = Str::slug($request->name);
        return response()->json(['slug' => $slug]);
    }
}
