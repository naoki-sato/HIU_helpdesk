<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers;

use App\Models\Message\MessageModel;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    // メッセージモデル
    private $message_model;

    public function __construct()
    {
        $this->message_model = new MessageModel();
    }

    /**
     * トップページ
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $info_mes = null; // 掲示板の内容

        // 掲示板に投稿した日から，30日以内のメッセージを取得する。
        $info_mes = $this->message_model->getMessage();

        // 取得したメッセージをトップページへ
        return view('welcome', ['info_mes' => $info_mes]);
    }


    /**
     * 掲示板にメッセージを登録する。
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){

        /* 変数部 */
        $post               = null;  // ポストされた全データ
        $info_mes           = null;  // 掲示板に登録する内容
        $staff_id           = null;  // 投稿したスタッフID
        $is_insert_success  = false; // インサートが正常にできたか

        // バリデーション
        $this->validate($request, [
            'info_mes'   => 'required|string'
        ]);

        $post       = $request->all();
        $info_mes   = $post['info_mes'];
        $staff_id   = $request->user()['id'];

        $is_insert_success = $this->message_model->insertMessage($info_mes, $staff_id);

        if (!$is_insert_success) {
            $request->session()->flash('alert_message', '投稿失敗しました。');
            return redirect('/')->withInput();
        }

        return redirect('/');
    }
}
