<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class RoomHotelCompleteFlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $hotelIds = DB::table('hotel_complete_flights')->pluck('id')->toArray();

        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('room__hotel_complete_flights')->insert([
                'capacety' => $faker->numberBetween(1, 4), // سعة الغرفة من 1 إلى 4 أشخاص
                'hotel_id' => $faker->randomElement($hotelIds), // اختيار معرف فندق عشوائي من القائمة
                'photo' => $faker->imageUrl(640, 480, 'room'), // توليد رابط صورة عشوائية للغرفة
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
