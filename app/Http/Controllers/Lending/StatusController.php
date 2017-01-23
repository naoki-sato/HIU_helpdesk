<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\Lending;

use App\Models\Status\StatusModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class StatusController extends Controller
{

    // ステータスモデル
    private $status_model;


    /**
     * StatusController constructor.
     */
    public function __construct()
    {
        $this->status_model = new StatusModel();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $table_data = $this->status_model->getIndexTableData();
        return view('status.index', ['data' => $table_data]);
    }


    /**
     * アイテムの貸出処理をする
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postRental(Request $request)
    {

        /* 変数部 */
        $is_insert_success  = false; // インサートできたかどうか
        $post               = null;  // ポストされた全データ
        $user_name          = null;  // 氏名
        $user_cd            = null;  // 学籍番号／教員番号
        $phone_no           = null;  // 電話番号
        $rental_items       = null;  // 貸出機材
        $staff_id           = null;  // 貸出したスタッフID
        $comment            = null;  // 備考

        // バリデーション
        $this->validate($request, 
                [
                    'user_name'   => 'required',
                    'user_cd'     => 'required|numeric',
                    'phone_no'    => 'required|numeric'
                ]);

        $post            = $request->all();
        $user_name       = $post['user_name'];
        $user_cd         = mb_convert_kana($post['user_cd'], 'sa');
        $phone_no        = $post['phone_no'];
        $rental_items    = self::removeBlank($post['rental_item']);
        $staff_id        = $request->user()['id'];
        $comment         = $post['note'];


        // 貸出アイテムのバリデーション(空もしくは空白などの場合は警告)
        if (empty($rental_items)) {
            session()->flash('alert_message', '<h3>貸出アイテムに値がありません。</h3>');
            return redirect()->back()->withInput();
        }

        // 貸出アイテムを貸出済みにする。
        $is_insert_success = $this->status_model->rentalItems(
            $user_name,
            $user_cd,
            $phone_no,
            $rental_items,
            $staff_id,
            $comment
        );

        // 貸出アイテムが登録されなかったときの処理
        if (!$is_insert_success) {
            session()->flash('alert_message', '<h3>既に貸出済みの機材または，未登録機材が含まれているので，登録処理できませんでした。</h3>');
            return redirect()->back()->withInput();

        } else {
            session()->flash('success_message', '<h3>貸出登録しました。</h3>');
            return redirect()->back();
        }

    }

    /**
     * 貸出済みアイテムの返却処理
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postReturn(Request $request)
    {

        /* 変数部 */
        $is_return_success  = false; // 正常にインサートできたかどうか
        $post               = null;  // ポストされた全データ
        $rental_items       = null;  // 貸出機材
        $staff_id           = null;  // 返却したスタッフID


        $post            = $request->all();
        $return_items    = self::removeBlank($post['return_item']);
        $staff_id        = $request->user()['id'];


        // 貸出アイテムのバリデーション(空もしくは空白などの場合は警告)
        if (empty($return_items)) {
            session()->flash('alert_message', '<h3>返却アイテムに値がありません。</h3>');
            return redirect()->back()->withInput();
        }


        // 返却アイテムを返却済みにする。
        $is_return_success = $this->status_model->returnItems($return_items, $staff_id);

        if($is_return_success){
            session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>貸出していない機材または，未登録機材が含まれているので，返却処理できませんでした。</h3>');
        }
        
        return redirect()->back();
    }

    /**
     * 半角・全角の空白を削除
     */
    private function removeBlank($array){

        foreach ($array as $key => $value) {
            $temp[] = trim(mb_convert_kana($value, "s"));
        }

        $result = array_filter($temp);

        return $result;
    }
}
