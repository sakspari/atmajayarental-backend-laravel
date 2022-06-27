<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pegawai::factory(25)->create();
        //        dummy employee for each resource with account
        Pegawai::create([
            'id' => 'ADM' . Carbon::now()->format('ymd') . '-' . sprintf("%03d", 26),
            'role_id' => 'ADM',
            'name' => 'Frederik Baptista Sakspari',
            'birthdate' => Carbon::parse('2000-08-05')->format('Y-m-d'),
            'gender' => 1,
            'address' => "Jl. Solo no.5, Depok, Sleman, yogyakarta",
            'phone' => '081245632345',
            'email' => 'admin@mail.com',
            'picture' => "/init-data/person/empty-profile-image.jpg",
            'created_at'=>Carbon::now()->subMinutes(4)
        ]);

        Pegawai::create([
            'id' => 'CS' . Carbon::now()->format('ymd') . '-' . sprintf("%03d", 27),
            'role_id' => 'CS',
            'name' => 'Claudia Putri',
            'birthdate' => Carbon::parse('2000-08-05')->format('Y-m-d'),
            'gender' => 0,
            'address' => "Jl. Solo no.5, Depok, Sleman, yogyakarta",
            'phone' => '081245632345',
            'email' => 'cs@mail.com',
            'picture' => "/init-data/person/empty-profile-image.jpg",
            'created_at'=>Carbon::now()->subMinutes(3)
        ]);

        Pegawai::create([
            'id' => 'MGR' . Carbon::now()->format('ymd') . '-' . sprintf("%03d", 28),
            'role_id' => 'MGR',
            'name' => 'Noah Maharani',
            'birthdate' => Carbon::parse('2000-08-05')->format('Y-m-d'),
            'gender' => 0,
            'address' => "Jl. Solo no.5, Depok, Sleman, yogyakarta",
            'phone' => '081245632345',
            'email' => 'manager@mail.com',
            'picture' => "/init-data/person/empty-profile-image.jpg",
            'created_at'=>Carbon::now()->subMinutes(2)
        ]);
    }
}
