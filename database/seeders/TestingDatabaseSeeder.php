<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class TestingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        \DB::table('attendance_records')->insert([
            [
                'user_id' => 1,
                'start_time' => null,
                'end_time' => null,
            ],
            [
                'user_id' => 2,
                'start_time' => now(),
                'end_time' => null,
            ],
            [
                'user_id' => 3,
                'start_time' => now()->subHour(),
                'end_time' => now(),
            ],
            [
                'user_id' => 4,
                'start_time' => null,
                'end_time' => now(),
            ],
        ]);
    }
}
