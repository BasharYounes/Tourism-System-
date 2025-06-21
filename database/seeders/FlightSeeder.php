<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) { // عدد السجلات التي تريد توليدها
            DB::table('flights')->insert([
                'flight_number' => strtoupper($faker->bothify('??###')), // توليد رقم رحلة مثل "DL456"
                'airline' => $faker->randomElement(['Delta Air Lines', 'United Airlines', 'American Airlines']), // اسم شركة الطيران
                'website' => $faker->url, // موقع شركة الطيران
                'departure_airport' => $faker->randomElement(['LAX', 'SFO', 'ORD']), // مطار المغادرة
                'departure_time' => $faker->dateTimeBetween('2024-01-01', '2024-12-31'), // وقت المغادرة
                'departure_date' => $faker->date('Y-m-d', '2024-12-31'), // تاريخ المغادرة
                'arrival_airport' => $faker->randomElement(['JFK', 'ATL', 'MIA']), // مطار الوصول
                'arrival_time' => $faker->dateTimeBetween('2024-01-01', '2024-12-31'), // وقت الوصول
                'duration' => $faker->randomElement(['5h', '6h', '7h']), // مدة الرحلة
                'price' => $faker->numberBetween(300, 1200), // السعر
                'reservation_type' => $faker->randomElement(['Economy', 'Business', 'First Class']), // فئة التذكرة
                'available_place' => $faker->numberBetween(50, 200), // المقاعد المتاحة
                'transport_id' => $faker->numberBetween(1,3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
