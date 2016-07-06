<?php

namespace App\Http\Controllers\Management\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReturnStaffController extends Controller
{


    private $return_staff_api;

    public function __construct()
    {
        $this->return_staff_api = new ReturnStaffApiController();
    }


    public function index(){
        return view('management.staff.return');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request){
        
        $this->validate($request, ['id'   => 'required|numeric']);

        $success = $this->return_staff_api->update($request);

        if($success){
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }



}
