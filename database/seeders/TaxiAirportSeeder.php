<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TaxiAirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $carIds = DB::table('cars')->pluck('id')->toArray();
        $airportIds = DB::table('air_ports')->pluck('id')->toArray();
        $driverIds = DB::table('drivers')->pluck('id')->toArray();

        foreach (range(1, 100) as $index) {
            DB::table('taxi_airports')->insert([
                'car_id' => $faker->randomElement($carIds), // اختيار معرف car_id من المعرفات الموجودة
                'airport_id' => $faker->randomElement($airportIds),
                'driver_id' => $faker->randomElement($driverIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

    }
    }
}
