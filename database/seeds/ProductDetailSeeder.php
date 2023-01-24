<?php

namespace Database\Seeders;

use App\ProductDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductDetail::factory()->count(1)->create();
    }
}
