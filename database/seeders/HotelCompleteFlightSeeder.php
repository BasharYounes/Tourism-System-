<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class HotelCompleteFlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();


        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('hotel_complete_flights')->insert([
                'name' => $faker->company,
                'address' => $faker->address,
                'city' => $faker->city,
                'country' => $faker->country,
                'star_rating' => $faker->randomFloat(1, 1, 5), // Random float number between 1 and 5
                'rating_average' => $faker->randomFloat(1, 1, 5), // Random float number between 1 and 10
                'photo' => $faker->imageUrl(640, 480, 'hotel'), // Generate a random image URL
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
