<?php

namespace Database\Factories;

use App\Models\Mitra;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tipeMobil = ['sedan', 'sedan', 'city car', 'city car', 'suv', 'suv', 'mpv', 'mpv'];
        $namaMobil = ['Toyota New Vios', 'Honda Civic', 'Toyota New Agya', 'Honda Brio', 'Toyota Rush', 'Toyota Fortuner', 'Toyota New Avanza', 'Toyota Alphard'];
        $fotoUrl = ['vios_merah.jpg', 'civic_hitam.webp', 'agya_putih.jpg', 'brio_kuning_jpg', 'rush_putih.jpg', 'fortuner_hitam.jpg', 'avanza_merah.png', 'alphard_putih.jpg'];
        $tarifPerHari = [400_000, 500_000, 250_000, 200_000, 1_000_000, 1_250_000, 300_000, 1_500_000];
        $warna = ['Merah', 'Hitam', 'Putih', 'Kuning', 'Putih', 'Hitam', 'Merah', 'Putih'];

        static $id_asset = 1;
        $is_sewaan = rand(0, 1); //1: sewaan 0: perusahaan
        $custom_created = Carbon::now()->subMonth(2);
        $customId = 'ASSET' . $custom_created->addDays($id_asset)->format('ymd') . '-' . sprintf("%03d", $id_asset++);

        return [
            'id_mobil' => $customId,
            'id_mitra' => $is_sewaan == 0 ? null : $this->faker->randomElement(Mitra::pluck('id')),
            'plat_mobil' => 'AB ' . $this->faker->unique()->randomNumber(4, true) . ' ' . strtoupper($this->faker->randomLetter() . $this->faker->randomLetter()),
            'no_stnk' => $this->faker->unique()->randomNumber(8, true),
            'nama_mobil' => $namaMobil[$id_asset - 2],
            'tipe_mobil' => $tipeMobil[$id_asset - 2],
            'jenis_aset' => $is_sewaan,
            'jenis_transmisi' => $this->faker->randomElement(['AT', 'MT', 'CVT']),
            'jenis_bahan_bakar' => $this->faker->randomElement(['pertalite', 'pertamax', 'premium']),
            'volume_bahan_bakar' => rand(30, 50),
            'warna_mobil' => $warna[$id_asset - 2],
            'fasilitas_mobil' => 'AC, Multimedia, Air Bag, '.$this->faker->randomElement(['Smart Sense', 'Safety Belt','Dolby Audio']),
            'volume_bagasi' => rand(12, 20),
            'kapasitas_penumpang' => rand(4, 8),
            'harga_sewa' => $tarifPerHari[$id_asset - 2],
            'servis_terakhir' => Carbon::now()->subHour($id_asset)->format('Y-m-d'),
            'foto_mobil' => '/init-data/asset/' . $fotoUrl[$id_asset - 2],
            'periode_mulai' => $is_sewaan == 0 ? null : Carbon::now()->subMonths(2)->format('Y-m-d'),
            'periode_selesai' => $is_sewaan == 0 ? null : Carbon::now()->addWeeks(rand(2, 8))->format('Y-m-d'),
            'created_at' => $custom_created->addDays($id_asset),
            'updated_at' => $custom_created->addDays($id_asset)
        ];
    }
}
