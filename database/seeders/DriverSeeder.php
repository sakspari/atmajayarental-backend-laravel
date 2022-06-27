<?php

namespace Database\Seeders;

use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Driver::factory(25)->create();

        $customId = 'DRV' . Carbon::now()->subMinutes(2)->format('ymd') . '-' . sprintf("%03d", 26);
        Driver::create([
            'id' => $customId,
            'name' => "Moses Saputra",
            'picture' => "/init-data/person/empty-profile-image.jpg",
            'address' => "Perumnas, Seturan, Maguwoharjo, Yogyakarta",
            'birthdate' => Carbon::parse('1999-01-19')->format('Y-m-d'),
            'gender' => rand(0, 1),
            'email' => 'driver@mail.com',
            'phone' => '0898237468',
            'language' => rand(0, 1) == 0 ? 'in' : 'inen',
            'price' => 200_000,
            'file_sim' => '/init-data/person/sim.jpg',
            'file_bebas_napza' => '/init-data/document/blank-doc.pdf',
            'file_sk_jiwa' => '/init-data/document/blank-doc.pdf',
            'file_sk_jasmani' => '/init-data/document/blank-doc.pdf',
            'file_skck' => '/init-data/document/blank-doc.pdf',
            'status' => 1,
            'created_at' => Carbon::now()->subMinutes(2),
            'updated_at' => Carbon::now()->subMinutes(2)
        ]);
    }
}
