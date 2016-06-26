<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegistrationUserApiController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return success : true, fail : false
     * 
     */
    public function store(Request $request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), 
                ['user_name' => 'required',
                 'user_cd'   => 'required|unique:users,user_cd',
                 'phone_no'  => 'required|numeric']);
        if($validation->fails()) return false;

        $post      = $request->all();
        $user_name = mb_convert_kana($post['user_name'], 'as');
        $user_cd   = mb_convert_kana($post['user_cd'], 'as');
        $phone_no  = mb_convert_kana($post['phone_no'], 'as');

        try{
            $user = new User;
            $user->user_name  = $user_name;
            $user->user_cd    = $user_cd;
            $user->phone_no   = $phone_no;
            $user->save();
        } catch(\PDOException $e) {
            return false;
        }
        return true;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $user_cd (ex. s1312007)
     * @return Json
     */
    public function show($user_cd)
    {

        $data = User::where('user_cd', '=', mb_convert_kana($user_cd, 'as'))->first();

        if (!$data) return null;

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return success : true, fail : false
     * @note 電話番号のみ更新
     */
    public function update(Request $request)
    {

        $post = $request->all();
        $user_cd  = mb_convert_kana($post['user_cd'], 'as');
        $phone_no = mb_convert_kana($post['phone_no'], 'as');

        $id = User::where('user_cd', '=', $user_cd)->first();
        try{
            User::where('id', '=', $id->id)
                ->update([
                    'phone_no' => $phone_no,
                ]);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

}
