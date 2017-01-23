<?php

namespace App\Http\Controllers\Management\Staff;

use App\Models\Management\Staff\BeBackStaffModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BeBackStaffController extends Controller
{


    private $back_staff_model;

    public function __construct()
    {
        $this->back_staff_model = new BeBackStaffModel();
    }


    public function index(){

        $data = null;

        $data = $this->back_staff_model->getDaletedStaff();
        return view('management.staff.return', ['data' => $data]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request){


        $is_back_success = false;
        
        $this->validate($request, ['id' => 'required|numeric']);

        $post = $request->all();
        $id = $post['id'];

        $is_back_success = $this->back_staff_model->beBack($id);

        if ($is_back_success) {
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        } else {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }



}
