<?php

namespace Database\Seeders;

use App\Models\TransaksiPeminjaman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransaksiPeminjaman::factory(100)->create();
    }
}
