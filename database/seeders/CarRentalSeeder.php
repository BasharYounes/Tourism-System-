<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CarRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $carIds = DB::table('cars')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) { // عدد السجلات التي تريد توليدها
            $fromDate = $faker->dateTimeBetween('now', '+1 month'); // تاريخ بداية الحجز عشوائي
            $toDate = (clone $fromDate)->modify('+'. $faker->numberBetween(1, 14) .' days'); // تاريخ نهاية الحجز بين 1 و 14 يوم بعد البداية

            DB::table('car_rentals')->insert([
                'car_id' => $faker->randomElement($carIds), // معرف السيارة (افتراضًا لديك 100 سيارة)
                'reservation_date' => $faker->date(), // تاريخ الحجز
                'from' => $fromDate->format('Y-m-d'), // تاريخ بداية الحجز
                'to' => $toDate->format('Y-m-d'), // تاريخ نهاية الحجز
                'cost' => $faker->randomFloat(2, 50, 500), // التكلفة بين 50 و 500
                'user_id' => $faker->numberBetween($userIds), // معرف المستخدم (افتراضًا لديك 50 مستخدم)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
