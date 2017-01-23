<?php

/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\Management\Staff;

use App\Models\Management\Staff\BeBackStaffModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BeBackStaffController extends Controller
{

    private $back_staff_model;


    /**
     * BeBackStaffController constructor.
     */
    public function __construct()
    {
        $this->back_staff_model = new BeBackStaffModel();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        // 引退したスタッフのデータ格納
        $data = null;

        // 引退したスタッフのデータ一覧取得
        $data = $this->back_staff_model->getDaletedStaff();

        return view('management.staff.return', ['data' => $data]);
    }


    /**
     * 引退したスタッフを復帰させる
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {


        $post            = null;  // ポストされた全データ
        $id              = null;  // スタッフID
        $is_back_success = false; // スタッフを復帰させることができたかどうか

        // バリデーション
        $this->validate($request, ['id' => 'required|numeric']);

        $post = $request->all();
        $id   = $post['id'];

        // スタッフを復帰させる
        $is_back_success = $this->back_staff_model->beBack($id);

        if ($is_back_success) {
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }

}
