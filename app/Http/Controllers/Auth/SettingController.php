<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class SettingController extends Controller
{
    
    /** 
     * getアクセス時
     * @param Request
     * @return view
     */
    public function getIndex(Request $request){
        return view('auth.setting', ['data' => Admin::find($request->user()['id'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect
     */
    public function postEdit(Request $request){

        $this->validate($request, ['email'    => 'unique:admins,email,' . $request->user()['id'],
                                   'phone_no' => 'required|numeric']);
        $post       = $request->all();
        $phone_no   = $post['phone_no'];
        $email      = $post['email'];
        $id         = $request->user()['id'];

        try{
            Admin::where('id', '=', $id)
                ->update([
                    'phone_no' => $phone_no,
                    'email' => $email
                ]);
        } catch(\Exception $e) {

            session()->flash('alert_message', '<h3>既にメールアドレスが登録されています。更新できませんでした。</h3>');
            return redirect()->back();
        }

        session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        return redirect()->back();

    }

    public function postResetPassword(Request $request){
        dd("ok");

    }

}
