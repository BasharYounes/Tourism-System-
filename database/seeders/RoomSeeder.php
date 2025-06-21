<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $hotelIds = DB::table('hotels')->pluck('id')->toArray();

        foreach (range(1, 100) as $index) { // Change 50 to however many entries you want
            DB::table('rooms')->insert([
                'capacety' => $faker->numberBetween(1, 4), // سعة الغرفة من 1 إلى 4 أشخاص
                'price' => $faker->numberBetween(50, 500), // سعر الغرفة من 50 إلى 500
                'reservation_type' => $faker->randomElement(['standard', 'Vip', 'prepaid']), // نوع الحجز
                'hotel_id' => $faker->randomElement($hotelIds), // اختيار معرف فندق عشوائي من القائمة
                'photo' => $faker->imageUrl(640, 480, 'room'), // توليد رابط صورة عشوائية للغرفة
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
