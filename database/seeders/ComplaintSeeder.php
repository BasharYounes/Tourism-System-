<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userIds = DB::table('users')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) { // عدد السجلات التي تريد توليدها
            DB::table('complaints')->insert([
                'comment' => $faker->sentence, // توليد جملة عشوائية تمثل الشكوى
                'user_id' => $faker->numberBetween($userIds), // معرف المستخدم (تأكد من تطابق العدد مع عدد المستخدمين المتاحين)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
