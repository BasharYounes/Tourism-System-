<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('air_ports')->insert([
                'name' => $faker->company,
                'city' => $faker->city,
                'country' => $faker->country,
                'photo' => $faker->imageUrl(640, 480, 'business'), // Generate a random image URL
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
