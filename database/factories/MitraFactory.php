<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mitra>
 */
class MitraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nik_mitra' => '5310'.$this->faker->unique()->randomNumber(8, true),
            'nama_mitra' => $this->faker->name(),
            'alamat_mitra' => $this->faker->address(),
            'no_telp_mitra' => $this->faker->phoneNumber(),
        ];
    }
}
