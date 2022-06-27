<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        static $id_int = 1;
        $email = $this->faker->unique()->email();
        $birthdate = $this->faker->date();
        $name = $this->faker->name();
        $custom_created = Carbon::now()->subWeek(2);
        $customId = 'CUS'.$custom_created->addHours($id_int)->format('ymd').'-'.sprintf("%03d",$id_int++);

        User::create([
            'name'=>$name,
            'level'=>'CUSTOMER',
            'email'=>$email,
            'password'=>bcrypt(Carbon::parse($birthdate)->format('dmY')),
        ]);

        return [
            'id' => $customId,
            'name' => $name,
            'picture'=>'/init-data/person/empty-profile-image.jpg',
            'address'=>$this->faker->address(),
            'birthdate'=>$birthdate,
            'gender'=>$this->faker->boolean(),
            'email'=>$email,
            'phone'=>$this->faker->phoneNumber(),
            'sim'=>'/init-data/person/id-card.jpg',
            'idcard'=>'/init-data/person/sim.jpg',
            'created_at'=>$custom_created->addHours($id_int),
            'updated_at'=>$custom_created->addHours($id_int)
        ];
    }
}
