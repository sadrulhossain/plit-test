<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UserGroupSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductDetailSeeder;
use Database\Seeders\ProductLogSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserGroupSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            ProductLogSeeder::class,
        ]);
    }
}
