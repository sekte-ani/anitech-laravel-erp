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
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $employees = Employee::with('roles')->orderBy('id', 'asc')->paginate(5);
        $users = User::with('employee')->orderBy('id', 'asc')->paginate(5);
        $roles = Role::where('name', '!=', 'Admin')->get();
        $divisions = Division::all();
        $status = ['Active', 'Non-Active'];

        return view('content.erp.erp-operational-employee', compact([
            'employees',
            'users',
            'roles',
            'divisions',
            'status',
        ]));
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

        return view('users.show', compact([
            'user',
        ]));
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
            ? $request->file('images')->store('profile-images', 'public') 
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

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'images' => 'nullable|image|file|max:5120|mimes:jpeg,png,jpg',
            'phone' => 'nullable',
            'address' => 'nullable',
            'bank_name' => 'nullable',
            'bank_account' => 'nullable',
        ]);

        if($request->file('images')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['images'] = $request->file('images') 
            ? $request->file('images')->store('profile-images', 'public') 
            : null;
        }

        $employee->update($validatedData);

        return redirect()->back()->with('success', 'Data Karyawan Berhasil Diubah');
    }

    public function destroy(string $id)
    {
        $deletedEmployee = Employee::findOrFail($id);
        $deletedEmployee->delete();

        return redirect()->back()->with('success', 'Data Karyawan Berhasil Dihapus');
    }

    public function checkSlugName(Request $request)
    {
        $slug = Str::slug($request->name);
        return response()->json(['slug' => $slug]);
    }
}
