<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Division;
use App\Models\Employee;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\RoleUser;
use App\Models\UserDivision;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $division = [
            'Manajemen Eksekutif',
            'Operasional',
            'Marketing',
            'Produk',
            'Teknologi Informasi',
        ];
        $slug_division = [
            'manajemen-eksekutif',
            'operasional',
            'marketing',
            'produk',
            'teknologi-informasi',
        ];

        foreach($division as $d){
            DB::table('divisions')->insert([
                'name' => $d,
                'slug' => $slug_division[array_search($d, $division)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $role = [
            'Admin',
            'Founder',
            'Pimpinan Utama',
            'Co-Founder',
            'Pimpinan Tim Operasional',
            'Pimpinan Tim Teknologi Informasi',
            'Pimpinan Tim Produk',
            'Pimpinan Tim Marketing',
            'Staff Operasional',
            'Staff Finance',
            'Mobile Engineer',
            'Back End Developer',
            'Front End Developer',
            'AI/ML Developer',
        ];
        $slug_role = [
            'admin',
            'founder',
            'pimpinan-utama',
            'co-founder',
            'pimpinan-tim-operasional',
            'pimpinan-tim-teknologi-informasi',
            'pimpinan-tim-produk',
            'pimpinan-tim-marketing',
            'staff-operasional',
            'staff-finance',
            'mobile-engineer',
            'back-end-developer',
            'front-end-developer',
            'ai-ml-developer',
        ];

        foreach($role as $r){
            DB::table('roles')->insert([
                'name' => $r,
                'slug' => $slug_role[array_search($r, $role)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Employee::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'division_id' => 1,
            'role_id' => 1,
        ]);

        RoleUser::create([
            'employee_id' => 1,
            'role_id' => 1,
        ]);

        UserDivision::create([
            'employee_id' => 1,
            'division_id' => 1
        ]);

        User::create([
            'email' => 'admin@mail.com',
            'password' => bcrypt('admindummy123'),
            'confirm_password' => 'admindummy123',
            'employee_id' => 1,
        ]);
    }
}
