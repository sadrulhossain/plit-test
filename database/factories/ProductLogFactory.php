<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\ProductLog;

class ProductLogFactory extends Factory
{
    protected $model = ProductLog::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => 1,
            'action' => '1',
            'taken_at' => date('Y-m-d H:i:s'),
            'taken_by' => 1,
        ];
    }
}
