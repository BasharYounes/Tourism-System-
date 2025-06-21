<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) { // عدد السجلات التي تريد توليدها
            DB::table('drivers')->insert([
                'name' => $faker->word, // اسم السيارة (يمكنك تخصيصه حسب الحاجة)
                'mobile' => $faker->phoneNumber, // رقم الهاتف
                'birth_date' => $faker->date(), // تاريخ الميلاد
                'nationality' => $faker->country, // الجنسية
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
