<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Product;
use Faker\Factory as Faker;
use Helper;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        $name = $faker->unique()->name();
        return [
            'name' => $name,
            'slug' => Helper::generateSlug($name),
            'quantity' => rand(1, 300),
            'price' => rand(10, 1000),
            'status' => '1',
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
