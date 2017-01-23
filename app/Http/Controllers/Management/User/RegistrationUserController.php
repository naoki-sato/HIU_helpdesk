<?php

namespace App\Http\Controllers\Management\User;

use App\Models\User\RegistrationModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;

class RegistrationUserController extends Controller
{


    private $registration_model;

    /**
     * RegistrationUserController constructor.
     */
    public function __construct()
    {
        $this->registration_model = new RegistrationModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        // 登録されている全てのユーザを取得する
        $data = $this->registration_model->getUserAll();
        return view('management.user.index', ['data' => $data]);
    }


    /**
     * ポストされたユーザをDBから削除する処理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(Request $request){

        $post               = null;  // ポストされた全データ
        $user_cd            = null;  // 学籍番号
        $is_deletable_user  = false; // ユーザ削除できるかどうか

        // バリデーション
        $this->validate($request, ['delete_user_cd'   => 'required|exists:users,user_cd|numeric']);

        $post    = $request->all();
        $user_cd = $post['delete_user_cd'];

        // 指定されてユーザを削除
        $is_deletable_user = $this->registration_model->dalete($user_cd);

        // 削除できたかどうか
        if ($is_deletable_user) {
            session()->flash('success_message', '<h3>ユーザ削除しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>ユーザ削除できませんでした。</h3>');
        }

        return redirect()->back();
    }


    /**
     * ポストされた学籍番号と氏名をDBに登録する
     *
     * @note 未登録->新規登録，既に登録済み->アップデート(*電話番号は消える)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request){


        $post      = null; // ポストされた全データ
        $user_name = null; // ユーザ氏名
        $user_cd   = null; // 学籍番号

        // バリデーション
        $this->validate($request,
            [
                'user_name' => 'required',
                // unique:users,user_cd　バリデートしてもしなくても
                // 'user_cd'   => 'required|unique:users,user_cd|numeric'
                'user_cd'   => 'required|numeric'
            ]);

        $post      = $request->all();
        $user_name = mb_convert_kana($post['user_name'], 'as');
        $user_cd   = mb_convert_kana($post['user_cd'], 'as');

        try {
            // 未登録なら新規に登録，登録済みならアップデート(ただし，電話番号はnullになる)
            $this->registration_model->replace($user_name, $user_cd, null);

        } catch(\PDOException $e) {
            session()->flash('alert_message', '<h3>登録できませんでした。</h3>');
            return redirect()->back()->withInput();
        }

        session()->flash('success_message', '<h3>正常に登録しました。</h3>');
        return redirect()->back();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @note TODO::リファクタしたほうがいい
     */
    public function postImport(Request $request)
    {

        $is_import_success = false;

        // バリデーション
        $this->validate($request, ['file_input'=> 'required']);


        $is_import_success = $this->registration_model->importCSV($request);

        // インポートできたかどうか
        if ($is_import_success) {
            session()->flash('success_message', '<h3>正常にImportできました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>Error: Importできませんでした。</h3>');
        }

        return back();
    }

    /**
     * CSVの書き方の例をエクスポートする
     */
    public function postExample() {

        $this->registration_model->exportCSV();
    }


}
