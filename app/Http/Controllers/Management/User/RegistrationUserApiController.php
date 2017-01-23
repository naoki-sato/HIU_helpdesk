<?php

namespace App\Http\Controllers\Management\User;

use App\Eloquents\User;
use App\Http\Controllers\Controller;

class RegistrationUserApiController extends Controller
{

    /**
     * 学籍番号から，ユーザ情報を取得し，jsonで返す
     *
     * @param  int  $user_cd (ex. 1312007)
     * @return Json
     */
    public function show($user_cd)
    {
        $data = User::where('user_cd', '=', mb_convert_kana($user_cd, 'as'))->first();

        if (!$data) return null;

        return response()->json($data);
    }

}
