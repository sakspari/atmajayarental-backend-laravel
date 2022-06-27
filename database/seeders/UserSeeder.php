<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => 'Frederik Baptista Sakspari',
            'level' => 'ADMIN',
            'email' => 'admin@mail.com',
            'password' => bcrypt('sakspari')
        ]);

        User::create([
            'name' => 'Claudia Putri',
            'level' => 'CS',
            'email' => 'cs@mail.com',
            'password' => bcrypt('sakspari')
        ]);

        User::create([
            'name' => 'Noah Maharani',
            'level' => 'MANAGER',
            'email' => 'manager@mail.com',
            'password' => bcrypt('sakspari')
        ]);

        User::create([
            'name' => 'Chintya Maria',
            'level' => 'CUSTOMER',
            'email' => 'customer@mail.com',
            'password' => bcrypt('sakspari')
        ]);

        User::create([
            'name' => 'Moses Saputra',
            'level' => 'DRIVER',
            'email' => 'driver@mail.com',
            'password' => bcrypt('sakspari')
        ]);
    }
}
