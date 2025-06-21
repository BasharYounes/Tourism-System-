<?php

namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class TaxiCarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('taxi_cars')->insert([
                'type_car' => $faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Convertible']), // نوع السيارة
                'color' => $faker->safeColorName, // لون السيارة
                'car_number' => strtoupper($faker->regexify('[A-Z]{2}-[0-9]{4}')), // رقم السيارة
                'photo' => $faker->imageUrl(640, 480, 'transport'), // توليد رابط صورة عشوائية
                'created_at' => now(),
                'updated_at' => now(),
            ]);

    }
}
}