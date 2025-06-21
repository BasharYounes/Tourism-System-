<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TaxiReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userIds = DB::table('users')->pluck('id')->toArray();   
        $Taxi_airportIds = DB::table('taxi_airports')->pluck('id')->toArray();   

        foreach (range(1, 50) as $index) { // عدد السجلات التي تريد توليدها
            DB::table('taxi_reservations')->insert([
                'from' => $faker->city, // مكان الانطلاق
                'to' => $faker->city, // مكان الوجهة
                'taxi_airport_id' => $faker->randomElement($Taxi_airportIds), // معرف التكسي أو المطار (تأكد من تطابق العدد مع عدد السيارات أو المطارات المتاحة)
                'user_id' => $faker->randomElement($userIds), // معرف المستخدم (تأكد من تطابق العدد مع عدد المستخدمين المتاحين)
                'date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'), // تاريخ الحجز عشوائي بين الآن وسنة من الآن
                'cost' => $faker->randomFloat(2, 10, 150), // تكلفة الحجز بين 10 و 150 مع دقتين عشريتين
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
