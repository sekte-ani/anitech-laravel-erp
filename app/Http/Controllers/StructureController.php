<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StructureController extends Controller
{
    public function index()
    {
        $role_user = RoleUser::orderBy('id', 'asc')->paginate(10);
        $roles = Role::all();
        $divisions = Division::all();

        return view('content.general.general-organization-struc', compact([
            'role_user',
            'roles',
            'divisions',
        ]));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $employee = Employee::findOrFail($id);

            $validatedData = $request->validate([
                'role_id' => 'required|array',
                'role_id.*' => 'exists:roles,id',
                'division_id' => 'required|array',
                'division_id.*' => 'exists:divisions,id',
            ]);

            $employee->roles()->sync($validatedData['role_id']);
            $employee->divisions()->sync($validatedData['division_id']);

            DB::commit();

            return redirect()->back()->with('success', 'Roles updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update roles: ' . $e->getMessage());
        }
    }
}
