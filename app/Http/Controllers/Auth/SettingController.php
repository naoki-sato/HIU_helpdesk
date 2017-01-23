<?php

namespace App\Http\Controllers\Auth;

use App\Eloquents\Admin;
use App\Models\Auth\SettingModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Hash;
use Mail;

class SettingController extends Controller
{

    // 設定モデル
    private $setting_model;


    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $this->setting_model = new SettingModel();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        // スタッフ情報
        $staff_info = null;

        // スタッフ情報を取得
        $staff_info = $this->setting_model->getAuthInfo($request->user()['id']);

        return view('auth.setting', ['data' => $staff_info]);
    }


    /**
     * Update the email and phone
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request)
    {

        /* 変数部 */
        $post               = null;  // ポストされた全データ
        $phone_no           = null;  // 電話番号
        $email              = null;  // メールアドレス
        $id                 = null;  // スタッフID
        $is_update_success  = false; // 正常にアップデートされたか

        // バリデーション
        $this->validate($request, [
            'email'    => 'unique:admins,email,' . $request->user()['id'],
            'phone_no' => 'required|numeric']);


        $post       = $request->all();
        $phone_no   = $post['phone_no'];
        $email      = $post['email'];
        $id         = $request->user()['id'];


        $is_update_success = $this->setting_model->updateAuthInfo($id, $phone_no, $email);

        if (!$is_update_success) {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        } else {
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        }

        return redirect()->back();
    }


    /**
     * スタッフ各自のパスワードリセット
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResetPassword(Request $request)
    {

        // バリデーション
        $this->validate($request, 
            [
                'pw'       => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

        $post = $request->all();
        $admin = Admin::findOrFail($request->user()['id']);

        // 現在のパスワードと入力されたパスワードが同じ場合は更新
        if (Hash::check($post['pw'], $admin['password'])) {

            $admin->fill([
                'password' => Hash::make($request->password)
            ])->save();

            // メール送信
            $data[] = null;
            Mail::queue('auth.emails.passwordchange', $data, function($message) use($admin)
            {
                $message->to($admin['email'], $admin['name'])->subject('Helpdesk Password Changed');
            });

            session()->flash('success_message', '<h3>パスワードを更新しました。</h3>');

        } else {
            session()->flash('alert_message', '<h3>パスワードが間違っています。</h3>');
        }

        return redirect()->back();

    }

}
