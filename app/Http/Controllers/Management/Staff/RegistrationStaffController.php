<?php

/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\Management\Staff;

use App\Models\Management\Staff\RegistrationStaffModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class RegistrationStaffController extends Controller
{

    private $registration_staff_model;

    /**
     * RegistrationStaffController constructor.
     */
    public function __construct()
    {
        $this->registration_staff_model = new RegistrationStaffModel();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 現在のスタッフを格納する
        $data = null;

        // 現在のスタッフ一覧の取得
        $data = $this->registration_staff_model->getStaffListData();

        return view('management.staff.index', ['data' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // 新スタッフを登録できたかどうか
        $is_insert_success = false;

        // バリデーション
        $this->validate($request,
                [
                    'staff_name' => 'required',
                    'staff_cd'   => 'required|unique:admins,staff_cd|numeric',
                    'phone_no'   => 'required|numeric',
                    'email'      => 'required|email|max:255|unique:admins,email',
                    'password'   => 'required|min:6|confirmed'
                ]);

        // 新スタッフをDBに登録
        $is_insert_success = $this->registration_staff_model->insertStaff($request);

        if ($is_insert_success) {
            session()->flash('success_message', '<h3>新規登録しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>新規登録できませんでした。</h3>');
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
        // スタッフ情報
        $staff = null;

        // IDからスタッフを取得
        $staff = $this->registration_staff_model->show($id);

        // データが空の場合は，スタッフは存在しない
        if (empty($staff)) {
            session()->flash('alert_message', '<h3>お探しのスタッフは見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        return view('management.staff.show', ['data' => $staff]);
    }


    /**
     * スタッフのロールを変更
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){

        $post               = null;  // ポストされた全データ
        $role               = null;  // 役割
        $id                 = null;  // ID
        $is_update_success  = false; // 変更できたかどうか


        // バリデーション
        $this->validate($request,
                ['role' => 'required|in:admin,manager,staff',
                 'id'   => 'required|numeric']);

        $post       = $request->all();
        $role       = $post['role'];
        $id         = $post['id'];

        // 不正にpostされていないかどうか
        $is_update_success = $this->postRoleCheck($request->user()['role'], $role);


        if ($is_update_success) {
            // スタッフの役割を更新
            $is_update_success = $this->registration_staff_model->updateRole($id, $role);
        }


        if ($is_update_success) {
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }


    /**
     * スタッフを引退させる(ソフトデリート)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {

        // 削除ができたかどうか
        $is_delete_success = false;

        $this->validate($request, ['staff_id' => 'required|numeric']);

        $post = $request->all();
        $id   = $post['staff_id'];


        $is_delete_success = $this->registration_staff_model->deleteStaff($id);



        if ($is_delete_success) {
            session()->flash('success_message', '<h3>正常に処理が完了しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }
        
        return redirect()->route('registration-staff.index');
    }


    private function postRoleCheck($my_role, $post_role) {


        if($my_role == 'staff' && $post_role != 'staff') {
            return false;

        } elseif ($my_role == 'manager' && $post_role == 'admin') {
            return false;

        } elseif ($my_role == 'admin') {
            return true;

        }else {
            return false;

        }

        return true;

    }


}
