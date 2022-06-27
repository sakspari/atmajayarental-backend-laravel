<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory(100)->create();

        $customId = 'CUS' . Carbon::now()->subMinutes(2)->format('ymd') . '-' . sprintf("%03d", 101);
        Customer::create([
            'id' => $customId,
            'name' => 'Chintya Maria',
            'picture' => '/init-data/person/empty-profile-image.jpg',
            'address' => 'Tambak Bayan, Babarsari, Depok, Sleman, Yogyakarta',
            'birthdate' => Carbon::parse('2000-01-20')->format('Y-m-d'),
            'gender' => 0,
            'email' => 'customer@mail.com',
            'phone' => '089456738462',
            'sim'=>'/init-data/person/id-card.jpg',
            'idcard'=>'/init-data/person/sim.jpg',
            'created_at' => Carbon::now()->subMinutes(2),
            'updated_at' => Carbon::now()->subMinutes(2)
        ]);

    }
}
