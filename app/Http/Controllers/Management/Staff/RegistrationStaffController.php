<?php

namespace App\Http\Controllers\Management\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class RegistrationStaffController extends Controller
{

    private $registration_staff_api;

    public function __construct()
    {
        $this->registration_staff_api = new RegistrationStaffApiController();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('management.staff.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,
                ['staff_name' => 'required',
                 'staff_cd'   => 'required|unique:admins,staff_cd|numeric',
                 'phone_no'   => 'required|unique:admins,phone_no|numeric',
                 'email'      => 'required|email|max:255|unique:admins,email',
                 'password'   => 'required|min:6|confirmed']);

        $success = $this->registration_staff_api->store($request);

        if($success){
            session()->flash('success_message', '<h3>新規登録しました。</h3>');
        }else{
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
        $json = $this->registration_staff_api->show($id);
        $data = json_decode($json->content(), true);


        if(empty($data)){
            session()->flash('alert_message', '<h3>お探しのスタッフは見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        return view('management.staff.show', ['data' => $data['data']]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request){
        
        $this->validate($request, 
                ['role' => 'required|in:admin,manager,staff',
                 'id'   => 'required|numeric']);

        $success = $this->registration_staff_api->update($request);

        if($success){
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        }else{
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
        $this->validate($request, ['staff_id' => 'required|numeric']);
        
        $success = $this->registration_staff_api->destroy($request);

        if($success){
            session()->flash('success_message', '<h3>正常に処理が完了しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }
        
        return redirect()->route('registration-staff.index');
    }


}
