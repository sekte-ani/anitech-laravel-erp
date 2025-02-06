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
        $roles = Role::all();

        return view('content.general.general-organization-struc', compact([
            'role_user',
            'roles',
        ]));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'role_id' => 'required|array',
            'role_id.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($validatedData['role_id']);

        return redirect()->back()->with('success', 'Roles updated successfully');
    }

    public function destroy(string $id)
    {
        $deletedRole = RoleUser::find($id);
        $deletedRole->delete();

        return redirect()->back()->with('success', 'Data Role Berhasil Dihapus');
    }
}
