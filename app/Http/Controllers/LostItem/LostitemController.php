<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\LostItem;

use App\Models\LostItem\LostItemModel;
use App\Models\User\RegistrationModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eloquents\Place;
use Input;

class LostItemController extends Controller
{

    // 場所s
    private $places;
    // 落し物モデル
    private $lost_item_model;


    /**
     * LostItemController constructor.
     */
    public function __construct()
    {
        $this->places           = Place::all();
        $this->lost_item_model  = new LostItemModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index()
    {
        // インデックスページのテーブル情報
        $table_data = $this->lost_item_model->getIndexTableData();

        return view('lostitem.index', ['places' => $this->places, 'data' => $table_data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return view
     * 
     */
    public function store(Request $request)
    {
        // 保存処理が正常にできたか
        $is_insert_success = false;

        // バリデーション
        $this->validate($request, 
            ['item_name'   => 'required|string',
             'place_id'    => 'required|numeric',
             'staff_id'    => 'required|numeric',
             'file_input'  => 'image|mimes:jpeg,jpg,png,gif'
            ]);

        $is_insert_success = $this->lost_item_model->insertLostItem($request);


        if ($is_insert_success) {
            session()->flash('success_message', '<h3>落し物の新規登録しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>落し物の新規登録できませんでした。</h3>');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return view
     */
    public function show($id)
    {
        // 指定された落し物情報を取得
        $item_data = $this->lost_item_model->showLostItem($id);

        // 該当する落し物がない場合
        if (empty($item_data)) {
            session()->flash('alert_message', '<h3>お探しの落し物は見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        // 引渡の未・済でテンプレートわけ
        if ($item_data['deleted_at']) { // 引渡処理済み
            return view('lostitem.finished', ['data' => $item_data, 'places' => $this->places]);
        } else { // 引渡未処理
            return view('lostitem.show', ['data' => $item_data, 'places' => $this->places]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request, $id)
    {
        // アップデート処理が正常にできたか
        $is_update_success = false;

        // バリデーション
        $this->validate($request, [
             'item_name'   => 'required|string',
             'place_id'    => 'required|numeric',
             'file_input'  => 'image|mimes:jpeg,jpg,png,gif'
             ]);


        $is_update_success = $this->lost_item_model->updateLostItem($request, $id);


        if ($is_update_success) {
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return redirect
     */
    public function destroy(Request $request, $id)
    {

        /* 変数部 */
        $post               = null; // ポストされた全データ
        $user_cd            = null; // 学籍番号
        $user_name          = null; // 氏名
        $phone_no           = null; // 電話番号
        $staff_id           = null; // スタッフID
        $is_destroy_success = null; // 処理が正常に行われたか


        // バリデーション
        $this->validate($request, [
                'delivery_staff_id' => 'required|numeric',
                'user_cd'           => 'required|numeric',
                'user_name'         => 'required|string',
                'phone_no'          => 'required|numeric']);


        $post       = $request->all();
        $user_cd    = $post['user_cd'];
        $user_name  = $post['user_name'];
        $phone_no   = $post['phone_no'];
        $staff_id   = $post['delivery_staff_id'];



        // DBに落し物情報が登録されていなければ，新規登録。 あれば情報を更新
        $registration_model = new RegistrationModel();
        $registration_model->replace($user_name, $user_cd, $phone_no);


        $is_destroy_success = $this->lost_item_model->destroyLostItem($id, $user_cd, $staff_id);


        if ($is_destroy_success) {
            session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }


        return redirect()->route('lost-item.index');
    }



}
