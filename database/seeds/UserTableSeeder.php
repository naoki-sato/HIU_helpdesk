<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $developers = [
            '1' => ['name'      => '今田 翔',
                    'email'     => 's1581105@s.do-johodai.ac.jp',
                    'staff_no'  => 's1581105',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

            '2' => ['name'      => '工藤 剛',
                    'email'     => 's1312092@s.do-johodai.ac.jp',
                    'staff_no'  => 's1312092',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  => bcrypt('password')], 

            '3' => ['name'      => '佐藤 直己',
                    'email'     => 's1312007@s.do-johodai.ac.jp',
                    'staff_no'  => 's1312007',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  =>  bcrypt('password')],

            '4' => ['name'      => 'Helpdesk',
                    'email'     => 'Helpdesk@s.do-johodai.ac.jp',
                    'staff_no'  => 'Helpdesk',
                    'role'      => 'admin',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

            '5' => ['name'      => 'hoge_staff',
                    'email'     => 'hoge_staff@s.do-johodai.ac.jp',
                    'staff_no'  => 'hoge_staff',
                    'role'      => 'staff',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

        ];

        // delete
        User::truncate();

        // insert
        foreach ($developers as $key => $value) {
            User::create([
                'name'          => $value['name'],
                'email'         => $value['email'],
                'staff_no'      => $value['staff_no'],
                'role'          => $value['role'],
                'phone_no'      => $value['phone'],
                'password'      => $value['password'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),

            ]);
        }
    }
}
























