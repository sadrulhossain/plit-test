<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\ProductDetail;
use Faker\Factory as Faker;

class ProductDetailFactory extends Factory
{
    protected $model = ProductDetail::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        return [
            'product_id' => 1,
            'description' => $faker->text(),
            'features' => $faker->text(),
            'image' => uniqid() . '_1.png',
        ];
    }
}
