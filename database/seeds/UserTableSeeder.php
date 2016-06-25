<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete
        User::truncate();

        // insert
        $faker = Faker::create('ja_JP');
        for ($i=2000; $i < 3500; $i++) { 
            User::create([
                'user_name' => $faker->name,
                'user_cd' => 's131'. $i,
                // 'user_cd' => 's'. $faker->numberBetween($min = 1310000, $max = 1610000),
                'phone_no' => $faker->phoneNumber,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
}
























