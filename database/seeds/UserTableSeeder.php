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
                'user_cd' => '131'. $i,
                'phone_no' => $faker->randomElement(['090', '080']) . $faker->numberBetween($min = 10000000, $max = 99999999),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
}
























