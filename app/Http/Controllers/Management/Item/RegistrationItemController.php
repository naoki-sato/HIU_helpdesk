<?php

namespace App\Http\Controllers\Management\Item;

use App\Models\Management\Item\ItemModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class RegistrationItemController extends Controller
{

    // 貸出用アイテムモデル
    private $registration_item_model;

    /**
     * RegistrationItemController constructor.
     */
    public function __construct()
    {
        $this->registration_item_model = new ItemModel();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 貸出用アイテムのデータ
        $data = null;

        // 貸出用アイテム一覧を取得
        $data = $this->registration_item_model->getIndexTableData();

        return view('management.item.index', ['data' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /* 変数部 */
        $post               = null;  // ポストされた全データ
        $item_cd            = null;  // アイテムコード
        $serial_cd          = null;  // シリアルコード
        $description        = null;  // 説明
        $is_insert_success  = false; // 正常にインサート処理が行われたかどうか

        // バリデーション
        $this->validate($request, 
                ['item_cd'     => 'required',
                 'serial_cd'   => 'required',
                 'description' => 'required']);

        $post        = $request->all();
        $item_cd     = $post['item_cd'];
        $serial_cd   = $post['serial_cd'];
        $description = $post['description'];

        $is_insert_success = $this->registration_item_model->registrationItem($item_cd, $serial_cd, $description);


        if($is_insert_success){
            session()->flash('success_message', '<h3>新規登録しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>新規登録できませんでした。</h3>');
        }
        return redirect()->back();
    }


}
