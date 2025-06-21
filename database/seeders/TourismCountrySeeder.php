<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TourismCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            DB::table('tourism_countries')->insert([
                'name' => $faker->company, // اسم المكان السياحي (يمكن أن يكون اسم عشوائي)
                'city' => $faker->city, // المدينة
                'country' => $faker->country, // الدولة
                'photo' => $faker->imageUrl(640, 480, 'city'), // رابط لصورة عشوائية
                'photo_dish' => $faker->imageUrl(640, 480, 'food'), // صورة لطبق الطعام المشهور
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
    }
}
