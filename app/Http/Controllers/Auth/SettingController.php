<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class SettingController extends Controller
{

    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                //update
                'email'    => 'sometimes|required|email',
                'phone_no' => 'sometimes|required|numeric'];
    }
    
    /** 
     * getアクセス時
     * @param Request
     * @return view
     */
    public function index(Request $request){
        return view('auth.setting', ['data' => Admin::find($request->user()['id'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect
     */
    public function store(Request $request){


        $this->validate($request, $this->validation_rules);

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


}
