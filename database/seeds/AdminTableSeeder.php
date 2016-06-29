<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Carbon\Carbon;

class AdminTableSeeder extends Seeder
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
                    'email'     => '1581105@s.do-johodai.ac.jp',
                    'staff_cd'  => '1581105',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

            '2' => ['name'      => '工藤 剛',
                    'email'     => '1312092@s.do-johodai.ac.jp',
                    'staff_cd'  => '1312092',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  => bcrypt('password')], 

            '3' => ['name'      => '佐藤 直己',
                    'email'     => '1312007@s.do-johodai.ac.jp',
                    'staff_cd'  => '1312007',
                    'role'      => 'manager',
                    'phone'     => '',
                    'password'  =>  bcrypt('password')],

            '4' => ['name'      => 'Helpdesk',
                    'email'     => 'Helpdesk@s.do-johodai.ac.jp',
                    'staff_cd'  => 'Helpdesk',
                    'role'      => 'admin',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

            '5' => ['name'      => 'hoge_staff',
                    'email'     => 'hoge_staff@s.do-johodai.ac.jp',
                    'staff_cd'  => 'hoge_staff',
                    'role'      => 'staff',
                    'phone'     => '',
                    'password'  => bcrypt('password')],

        ];

        // delete
        Admin::truncate();

        // insert
        foreach ($developers as $key => $value) {
            Admin::create([
                'name'          => $value['name'],
                'email'         => $value['email'],
                'staff_cd'      => $value['staff_cd'],
                'role'          => $value['role'],
                'phone_no'      => $value['phone'],
                'password'      => $value['password'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),

            ]);
        }
    }
}
