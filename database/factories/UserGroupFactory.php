<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\UserGroup;
use Faker\Factory as Faker;

class UserGroupFactory extends Factory
{
    protected $model = UserGroup::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        return [
            'name' => $faker->unique()->name(),
        ];
    }
}
