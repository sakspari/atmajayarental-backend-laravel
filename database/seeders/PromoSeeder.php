<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Promo::create([
            'kode_promo' => 'MHS',
            'jenis_promo' => 'Pelajar & Mahasiswa',
            'deskripsi_promo' => 'Promo bagi customer yang berusia mulai dari 17-22 tahun dan memiliki kartu identitas pelajar/mahasiswa, mendapat diskon sebesar 20%',
            'persen_diskon' => 20,
            'status_promo' => 1,
        ]);

        Promo::create([
            'kode_promo' => 'BDAY',
            'jenis_promo' => 'Ulang Tahun',
            'deskripsi_promo' => 'Promo bagi customer yang sedang berulang tahun, mendapat diskon sebesar 15%',
            'persen_diskon' => 15,
            'status_promo' => 1,
        ]);

        Promo::create([
            'kode_promo' => 'MDK',
            'jenis_promo' => 'Mudik',
            'deskripsi_promo' => 'Promo berlaku selama masa libur Lebaran dan Nataru, mendapat diskon sebesar 25%',
            'persen_diskon' => 25,
            'status_promo' => 1,
        ]);

        Promo::create([
            'kode_promo' => 'WKN',
            'jenis_promo' => 'Weekend',
            'deskripsi_promo' => 'Promo berlaku selama hari Sabtu dan Minggu, mendapat diskon sebesar 10%',
            'persen_diskon' => 10,
            'status_promo' => 1,
        ]);

    }
}
