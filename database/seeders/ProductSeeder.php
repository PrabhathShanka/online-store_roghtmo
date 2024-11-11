<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 200; $i++) {
            DB::table('products')->insert([
                'name' => $faker->word . ' ' . $faker->word, // Generating a product name with two random words
                'description' => $faker->text(200), // Generate a random text for description
                'price' => $faker->randomFloat(2, 1, 1000), // Random price between 1 and 1000
                'stock' => $faker->numberBetween(0, 100), // Random stock between 0 and 100
                'category_id' => $faker->numberBetween(1, 8), // Random category_id between 1 and 8
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
