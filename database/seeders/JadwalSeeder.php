<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  generate jadwal (selasa - minggu) sesi 1 dan 2
        $hari_kerja = ["selasa", "rabu", "kamis", "jumat", "sabtu", "minggu"];
        $sesi = [1, 2];

        foreach ($hari_kerja as $hari){
            foreach ($sesi as $s){
                Jadwal::create([
                    'hari'=>$hari,
                    'sesi'=>$s
                ]);
            }
        }

    }
}
