<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $id_pegawai = 1;
        $role_id = rand(0, 1);
        $role = $role_id == 0 ? 'ADM' : 'CS';
        $custom_created = Carbon::now()->subWeek(2);

        //generate Custom id for customer
        $customId =  $role.$custom_created->addHours($id_pegawai)->format('ymd') . '-' . sprintf("%03d", $id_pegawai++);
        return [
            'id' => $customId,
            'name' => $this->faker->name(),
            'picture' => '/init-data/person/empty-profile-image.jpg',
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->date,
            'gender' => $this->faker->boolean,
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'role_id' => $role,
            'created_at'=>$custom_created->addHours($id_pegawai),
            'updated_at'=>$custom_created->addHours($id_pegawai)
        ];
    }
}
