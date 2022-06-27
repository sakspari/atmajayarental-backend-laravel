<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $id_driver = 1;
        $custom_created = Carbon::now()->subWeek(2);
        $customId = 'DRV' . $custom_created->addHours($id_driver)->format('ymd') . '-' . sprintf("%03d", $id_driver++);
        return [
            'id' => $customId,
            'name' => $this->faker->name(),
            'picture' => '/init-data/person/empty-profile-image.jpg',
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->date,
            'gender' => $this->faker->boolean,
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'language' => $this->faker->boolean ? 'in' : 'inen',
            'price' => range(15,25)[rand(0,9)]*10_000,
            'file_sim' => '/init-data/person/sim.jpg',
            'file_bebas_napza' => '/init-data/document/blank-doc.pdf',
            'file_sk_jiwa' => '/init-data/document/blank-doc.pdf',
            'file_sk_jasmani' => '/init-data/document/blank-doc.pdf',
            'file_skck' => '/init-data/document/blank-doc.pdf',
            'status' => 1,
            'created_at' => $custom_created->addHours($id_driver),
            'updated_at' => $custom_created->addHours($id_driver)
        ];
    }
}
