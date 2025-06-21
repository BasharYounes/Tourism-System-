<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CompleteFlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $hotelIds = DB::table('hotel_complete_flights')->pluck('id')->toArray();

        foreach (range(1, 100) as $index) { 
        DB::table('complete__flights')->insert([
            'name' => $faker->sentence(2), // توليد اسم الرحلة
            'destination' => $faker->city . ', ' . $faker->country, // توليد وجهة الرحلة
            'travel_dates_departure' => $faker->dateTimeBetween('2024-01-01', '2024-12-31')->format('Y-m-d'), // تاريخ المغادرة
            'travel_dates_return' => $faker->dateTimeBetween('2024-01-01', '2025-01-01')->format('Y-m-d'), // تاريخ العودة
            'reservation_type' => $faker->randomElement(['Lodge', 'Hotel', 'Resort']), // نوع الإقامة
            'hotel_id' => $faker->randomElement($hotelIds), // اسم الفندق
            'nights' => $faker->numberBetween(3, 14), // عدد الليالي
            'activities' => json_encode([
                $faker->sentence(3),
                $faker->sentence(3),
                $faker->sentence(3),
                $faker->sentence(3),
            ]), // الأنشطة المضمنة
            'inclusions' => json_encode([
                'Round-trip airfare',
                'All meals and beverages',
                'Guided tours',
                'Park entrance fees',
            ]), // المزايا المضمنة
            'price' => $faker->numberBetween(2000, 8000), // السعر
            'available_place' => $faker->numberBetween(5, 20), // الأماكن المتاحة
            'transport_id' => $faker->numberBetween(1, 3), // شركة النقل
            'transport_company' => $faker->company, // شركة النقل
            'famous' => $faker->randomElement([
                'Sushi', 'Pizza', 'Paella', 'Tacos', 'Croissants', 'Biryani', 'Pasta', 'Dim Sum'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }}
}
