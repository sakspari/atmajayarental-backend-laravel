<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TransaksiPeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $custom_inc = 1;
        $transaction_date = Carbon::now()->subWeeks(rand(2, 16));
        $custom_created = Carbon::now()->subMonths(5);
        $duration = rand(2, 7);
        $is_driver = rand(0, 1);
//        $driver = $this->faker->randomElement(Driver::all());
        $driver[] = Driver::inRandomOrder()->first();
//        $pegawai_cs = $this->faker->randomElement(Pegawai::where('role_id', '=', 'CS')->get());
        $pegawai_cs[] = Pegawai::inRandomOrder()->first();
//        $car = $this->faker->randomElement(Mobil::all());
        $car[] = Mobil::inRandomOrder()->first();
//        $total = $is_driver == 0 ? $car->harga_sewa * $duration : $car->harga_sewa * $duration + $driver->price * $duration;
        $total = $duration + $driver[0]->price * $duration;
        $customId = "TRN" . $transaction_date->format('ymd') . '0' . $is_driver . '-' . sprintf("%03d", $custom_inc++);
//        echo $customId.';  ';

        return [
            'id_transaksi' => $customId,
            'id_mobil' => $car[0]->id_mobil,
            'id_customer' => $this->faker->randomElement(Customer::pluck('id')),
            'id_driver' => $is_driver == 1 ? $driver[0]->id : null,
            'id_pegawai' => $pegawai_cs[0]->id,
            'kode_promo' => null,
            'waktu_transaksi' => $transaction_date,
            'waktu_mulai' => $transaction_date->addDays(2),
            'waktu_selesai' => $transaction_date->addDays(2 + $duration),
            'waktu_pengembalian' => $transaction_date->addDays(2 + $duration),
            'subtotal_mobil' => $car[0]->harga_sewa * $duration,
            'subtotal_driver' => $is_driver == 1 ? $driver[0]->price * $duration : null,
            'total_denda' => null,
            'total_diskon' => null,
            'grand_total' => $total,
            'metode_pembayaran' => 1,
            'bukti_pembayaran' => '/init-data/transaction/payment-inv.png',
            'status_transaksi' => '5',
            'rating_driver' => $is_driver == 1 ? rand(1, 5) : null,
            'review_driver' => $is_driver == 1 ? 'contoh review driver' : null,
            'created_at' => $custom_created->addDays($custom_inc),
            'updated_at' => $custom_created->addDays($custom_inc)
        ];
    }
}
