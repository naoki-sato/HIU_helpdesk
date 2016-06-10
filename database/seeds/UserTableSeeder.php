<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 削除
        DB::table('users')->truncate();

        // insert
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@s.do-johodai.ac.jp',
            'staff_no' => 'admin',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'naoki',
            'email' => 's1312007@s.do-johodai.ac.jp',
            'staff_no' => 's1312007',
            'role' => 'manager',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'staff',
            'email' => 'staff@s.do-johodai.ac.jp',
            'staff_no' => 'staff',
            'role' => 'staff',
            'password' => bcrypt('password'),
        ]);
    }
}
























