<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Member;

class MemberFactory extends Factory
{
    protected $model = Member::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'age' => rand(15, 50),
            'email' => $this->faker->email,
            'mobile' => $this->faker->phoneNumber,
            'address' => $this->faker->address
        ];
    }
}
