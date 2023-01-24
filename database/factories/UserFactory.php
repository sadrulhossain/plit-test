<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_group_id' => 1,
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'phone' => '01' . rand(31, 99) . rand(1111111, 9999999),
            'photo' => uniqid() . '_1.png', 
            'password' => Hash::make('aA1!12345'),
            'status' => '1',
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
