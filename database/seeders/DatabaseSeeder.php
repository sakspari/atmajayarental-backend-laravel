<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Jadwal;
use App\Models\Pegawai;
use App\Models\Promo;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        fake customer




        $this->call([
            RoleSeeder::class,
            JadwalSeeder::class,
            PegawaiSeeder::class,
            CustomerSeeder::class,
            DriverSeeder::class,
            UserSeeder::class,
            PromoSeeder::class,
            MitraSeeder::class,
            MobilSeeder::class,
            TransaksiSeeder::class
        ]);

    }
}
