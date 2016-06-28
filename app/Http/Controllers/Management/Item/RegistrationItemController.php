<?php

namespace App\Http\Controllers\Management\Item;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegistrationItemController extends Controller
{
    private $registration_item_api;

    public function __construct()
    {
        $this->registration_item_api = new RegistrationItemApiController();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('management.item.index');
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
                ['item_cd'     => 'required|unique:items,item_cd',
                 'serial_cd'   => 'required',
                 'description' => 'required']);

        $success = $this->registration_item_api->store($request);

        if($success){
            session()->flash('success_message', '<h3>新規登録しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>新規登録できませんでした。</h3>');
        }
        return redirect()->back();
    }


}
