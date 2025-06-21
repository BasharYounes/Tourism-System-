<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('air_lines')->insert([
                'name' => $faker->company,
                'transport_id' => $faker->numberBetween(1, 3), // شركة النقل
                'photo' => $faker->imageUrl(640, 480, 'airline'), // Generate a random image URL
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
