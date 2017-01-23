<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Models\User;

use App\Eloquents\User;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Input;

class RegistrationModel extends Model
{

    public function getUserAll()
    {
        return User::all();
    }

    /**
     * ユーザテーブルに，指定された学籍番号がなければinsert，あればupdateする
     *
     * @param $user_name
     * @param $user_cd
     * @param $phone_no
     */
    public function replace($user_name, $user_cd, $phone_no)
    {
        // テーブルになければinsert，あればupdate
        User::updateOrCreate(
            ['user_cd' => $user_cd],
            [
                'user_name' => $user_name,
                'user_cd'   => $user_cd,
                'phone_no'  => $phone_no
            ]
        );
    }


    /**
     * ユーザの学籍番号を元に，ユーザ情報を削除する
     *
     * @param $user_cd
     * @return bool
     */
    public function dalete($user_cd)
    {
        try{
            User::where('user_cd', '=', $user_cd)->forceDelete();
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }



    public function importCSV(Request $request)
    {




        $user_all = self::getUserAll();

        $csv_users = [];
        $import_users = [];
        $database_users = [];



        // ファイルインポート
        if(Input::hasFile('file_input')){
            $path = Input::file('file_input')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            if(!empty($data) && $data->count()){

                // 既にあるDBのユーザ情報をまとめる
                foreach ($user_all as $user) {
                    $database_users += [
                        $user['user_cd'] =>
                            [
                                'user_name'  => $user['user_name'],
                                'user_cd'    => $user['user_cd'],
                                'phone_no'   => $user['phone_no'],
                                'idm'        => $user['idm'],
                                'created_at' => $user['created_at'],
                                'updated_at' => $user['updated_at']
                            ]
                    ];
                }

                // ポストされたCSVの情報をまとめる
                foreach ($data as $user) {
                    $csv_users += [
                        $user->user_cd =>
                            [
                                'user_name'  => $user->user_name,
                                'user_cd'    => (int)$user->user_cd,
                                'phone_no'   => $user->phone_no,
                                'idm'        => $user->idm,
                                'created_at' => $user->created_at,
                                'updated_at' => $user->updated_at
                            ]
                    ];
                }


                // 既存の情報とCSVの情報をまとめる
                $import_users = $database_users + $csv_users;

                try {
                    // 全消し
                    User::truncate();

                    // DBにまとめた情報をinsert
                    User::insert($import_users);

                } catch (\Exception $e) {

                    return false;
                }
            }
        }

        return true;

    }


    public function exportCSV()
    {

        $users = [['user_cd', 'user_name'], ['1234567', '情報 太郎'], ['1312007', '佐藤 直己']];

        Excel::create('user_import_example', function($excel) use($users) {
            $excel->sheet('1', function($sheet) use($users){
                $sheet->fromArray($users, null, 'A1', false, false);
            });
        })->export('csv');
    }

}
