<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Models\Auth;

use App\Eloquents\Admin;
use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{

    /**
     * helpdeskスタッフの情報を取得
     *
     * @param $id
     * @return null or スタッフ情報
     */
    public function getAuthInfo($id)
    {
        // スタッフ情報
        $auth_info = null;

        // スタッフ情報を取得
        $auth_info = Admin::find($id);

        return $auth_info;
    }


    /**
     * Update the email and phone
     *
     * @param $id
     * @param $phone_no
     * @param $email
     * @return bool
     */
    public function updateAuthInfo($id, $phone_no, $email)
    {
        try{
            Admin::where('id', '=', $id)
                ->update([
                    'phone_no' => $phone_no,
                    'email' => $email
                ]);

        } catch(\Exception $e) {
            return false;
        }

        return true;
    }

}
