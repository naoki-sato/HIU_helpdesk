<?php

namespace App\Http\Controllers\Management\Staff;

use App\Models\Management\Staff\RegistrationStaffModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class RegistrationStaffController extends Controller
{

    private $registration_staff_model;

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

        $data = null;

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

        $is_insert_success = false;



        $this->validate($request,
                [
                    'staff_name' => 'required',
                    'staff_cd'   => 'required|unique:admins,staff_cd|numeric',
                    'phone_no'   => 'required|numeric',
                    'email'      => 'required|email|max:255|unique:admins,email',
                    'password'   => 'required|min:6|confirmed'
                ]);

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
        $staff = null;


        $staff = $this->registration_staff_model->show($id);


        if (empty($staff)) {
            session()->flash('alert_message', '<h3>お探しのスタッフは見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        return view('management.staff.show', ['data' => $staff]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request){


        $post               = null;
        $role               = null;
        $id                 = null;
        $is_update_success  = false;
        
        $this->validate($request,
                ['role' => 'required|in:admin,manager,staff',
                 'id'   => 'required|numeric']);

        $post       = $request->all();
        $role       = $post['role'];
        $id         = $post['id'];

        $is_update_success = $this->registration_staff_model->updateRole($id, $role);

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
    public function destroy(Request $request)
    {

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


}
