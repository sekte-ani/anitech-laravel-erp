<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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

        User::create([
            'name' => 'admin',
            'slug' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('admindummy123'),
            'role_id' => 1
        ]);

        $serviceNames = [
            'Web Development',
            'Mobile App Development',
            'UI/UX Design',
            'AI/ML'
        ];

        $serviceDescriptions = [
            'We specialize in developing innovative mobile applications for Android platforms, ensuring seamless performance and engaging user experiences.',
            'From concept to launch, we create stunning websites that are not only visually impressive but also highly functional and user-friendly.',
            'Our team of designers is dedicated to creating intuitive and visually appealing interfaces that enhance user engagement and satisfaction.',
            'Leverage the power of AI and Machine Learning to drive innovation, optimize operations, and unlock valuable insights.'
        ];

        $serviceImages = [
            'services/anis-1.png',
            'services/anis-2.png',
            'services/anis-3.png',
            'services/anis-4.png'
        ];

        foreach ($serviceNames as $index => $serviceName){
            Service::insert([
                'name' => $serviceName,
                'name_slug' => str()->slug($serviceName),
                'description' =>  $serviceDescriptions[$index],
                'image' => $serviceImages[$index],
                'created_by' => 1,
            ]);
        }
    }
}
