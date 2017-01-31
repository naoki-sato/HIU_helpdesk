<?php

namespace App\Http\Controllers\Management\Shift;

use App\Models\Management\Shift\ShiftModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Storage;
use Image;
use Input;

class ShiftController extends Controller
{
    // シフトモデル
    private $shift_model;

    /**
     * ShiftController constructor.
     */
    public function __construct()
    {

        $this->shift_model = new ShiftModel();
    }

    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $file_name = null;

        $file_name = $this->shift_model->getNowImagePath();

        return view('management.shift.index', ['image_name' => $file_name]);
    }


    public function store(Request $request)
    {

        // 保存処理が正常にできたか
        $is_insert_success = false;

        // バリデーション
        $this->validate($request,
            [
                'file_input'  => 'required|image|mimes:jpeg,jpg,png,gif'
            ]);

        $is_insert_success = $this->shift_model->insertImageName($request);


        if ($is_insert_success) {
            session()->flash('success_message', '<h3>アップロード成功しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>アップロード失敗しました。</h3>');
        }

        return redirect()->back();

    }

}
