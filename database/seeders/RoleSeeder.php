<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'role_id' => 'ADM',
            'role_name' => "Admin",
        ]);

        Role::create([
            'role_id' => 'CS',
            'role_name' => "Customer Service",
        ]);

        Role::create([
            'role_id' => 'MGR',
            'role_name' => "Manager",
        ]);
    }
}
