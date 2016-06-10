<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Student;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete
        Student::truncate();

        // insert
        $faker = Faker::create('ja_JP');
        for ($i=0; $i < 300; $i++) { 
            Student::create([
                'student_name' => $faker->name,
                'student_no' => 's'. $faker->numberBetween($min = 1310000, $max = 1610000),
                'phone_no' => $faker->phoneNumber,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
}
