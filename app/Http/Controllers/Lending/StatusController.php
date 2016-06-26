<?php

namespace App\Http\Controllers\Lending;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;

class StatusController extends Controller
{
    private $lend_item_api;

    public function __construct()
    {
        $this->lend_item_api = new StatusApiController();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('status.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLend(Request $request)
    {
        $this->validate($request, 
                ['user_name'   => 'required',
                 'user_cd'     => 'required',
                 'phone_no'    => 'required|numeric']);

        $success = $this->lend_item_api->store($request);

        if($success){
            session()->flash('success_message', '<h3>貸出登録しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>既に貸出済みの機材等があり，貸出登録できませんでした。</h3>');
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
    public function postReturn(Request $request)
    {

        $success = $this->lend_item_api->destroy($request);

        if($success){
            session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>貸出していない機材等があり，処理ができませんでした。</h3>');
        }
        
        return redirect()->back();
    }
}
