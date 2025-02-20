<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Role;
use App\Models\Testimonial;
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
            'Staff Marketing',
            'Mobile Engineer',
            'Back End Developer',
            'Front End Developer',
            'UI/UX Designer',
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
            'staff-marketing',
            'mobile-engineer',
            'back-end-developer',
            'front-end-developer',
            'ui-ux-designer',
            'ai-ml-developer',
        ];

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

        $category = [
            'Masa Aktif',
            'Gaji',
            'Bonus',
            'Sisa Saldo',
            'Pendapatan',
            'Investasi Modal',
            'Domain',
            'Server',
            'Tools',
        ];

        foreach($category as $c){
            DB::table('category_expenses')->insert([
                'name' => $c,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
            ['services/anis-1.1.png', 'services/anis-1.2.png'],
            ['services/anis-2.1.png', 'services/anis-2.2.png'],
            ['services/anis-3.1.png', 'services/anis-3.2.png'],
            ['services/anis-4.1.png', 'services/anis-4.2.png'],
        ];

        $packageNames = [
            'Paket Basic',
            'Paket Standar',
            'Paket Professional'
        ];

        $packagePrices = [
            '2500000',
            '5000000',
            '10000000'
        ];

        $packageFeatures = [
            'Desain responsif'
        ];

        foreach ($serviceNames as $index => $serviceName){
            Service::insert([
                'name' => $serviceName,
                'name_slug' => str()->slug($serviceName),
                'short_description' =>  $serviceDescriptions[$index],
                'long_description' =>  $serviceDescriptions[$index],
                'images' => json_encode($serviceImages[$index]),
                'created_by' => 1,
            ]);

            if($index < 3) {
                Package::insert([
                    'service_id' => $index + 1,
                    'name' => $packageNames[$index],
                    'name_slug' => str()->slug($packageNames[$index]),
                    'price' => $packagePrices[$index],
                    'features' => $packageFeatures[0],
                    'created_by' => 1,
                ]);
            }
        }

        $portfolioNames = [
            'Pencatatan Transaksi Warteug',
            'SYMALAS',
            'Chef Kos-an',
            'MyAbsen',
            'LahanTani'
        ];

        $portfolioDescriptions = [
            'Pencatatan Transaksi Warteug adalah untuk mempermudah owner dalam hal pencatatan stok yang tersedia dan pencatatan transaksi secara otomatis.',
            'SYMALAS adalah applikasi mobile yang berguna untuk mempermudah antara dosen dan mahasiswa secara perkelas yang digunakan untuk mengumpulkann tugas.',
            'Chef Kos-an adalah website untuk pengguna ketika mencari sebuah resep masak terutama target nya adalah anak kos.',
            'MyAbsen adalah app mobile yang digunakan untuk mempermudah mengelola absen antara perusahaan dan pegawai.',
            'Pencatatan Transaksi Warteug adalah untuk mempermudah owner dalam hal pencatatan stok yang tersedia dan pencatatan transaksi secara otomatis.'
        ];

        $portfolioImages = [
            'portfolios/anis-1.png',
            'portfolios/anis-2.png',
            'portfolios/anis-3.png',
            'portfolios/anis-4.png',
            'portfolios/anis-5.png'
        ];

        foreach($portfolioNames as $index => $portfolioName) {
            Portfolio::insert([
                'name' => $portfolioName,
                'name_slug' => str()->slug($portfolioName),
                'description' => $portfolioDescriptions[$index],
                'image' => $portfolioImages[$index],
                'created_by' => 1
            ]);

            DB::table('portfolio_service')->insert([
                'portfolio_id' => $index + 1,
                'service_id' => 1,
            ]);
        }

        $testimonialNames = [
            'Adhitya Caesar',
            'Rachel Siska',
            'Nasir Lepkom'
        ];

        $testimonialJobs = [
            'Information System Student'
        ];

        $testimonialMessages = [
            'Engga late respond, komunikatif, mau setup meeting yang jelas dan kelihatan banget mau kita puas. Recommended untuk anak gundar',
            'Hasil projectnya sesuai sama yang gue mau, cepet juga, komunikasinya oke banget, adain gmeet biar projectnya dapet arahan yang jelas, dikasih tau arahan yang baik gimana buat projectnya, kakaknya baik baik',
            'Pengerjaannya cepet, kirain bakal ga kekejar dlnya tapi beberapa hari sebelum dl pun selesai, jadi sempet ngajuin revisi. Komunikasi oke, apa yang kudu ditanyain ditanya semua, jadi bisa minimalisir misunderstood. Overall good sih 9/10 lah yaa',
        ];

        $testimonialImages = [
            'testimonials/anis-1.png',
            'testimonials/anis-2.png',
            'testimonials/anis-3.png',
        ];

        foreach($testimonialNames as $index => $testimonialName) {
            Testimonial::create([
                'name' => $testimonialName,
                'job' => $testimonialJobs[0],
                'message' => $testimonialMessages[$index],
                'image' => $testimonialImages[$index],
                'created_by' => 1,
            ]);
        }
    }
}
