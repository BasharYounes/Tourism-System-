<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $carClasses = ['Family', 'Sport', 'Luxury', 'Economy', 'SUV']; // فئات السيارات

        foreach (range(1, 100) as $index) { // عدد السجلات التي تريد توليدها
            DB::table('cars')->insert([
                'type_car' => $faker->word, // نوع السيارة (مثل: sedan, coupe)
                'color' => $faker->safeColorName, // اللون
                'monthly_rent' => $faker->numberBetween(300, 2000), // التكلفة الشهرية
                'class' => $faker->randomElement($carClasses), // فئة السيارة
                'car_number' => $faker->unique()->bothify('??-###-???'), // رقم السيارة (مثل: AB-123-CD)
                'people_number' => $faker->numberBetween(1, 5), // عدد الأشخاص
                'photo' => $faker->imageUrl(640, 480, 'cars'), // صورة السيارة
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
