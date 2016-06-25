<?php

namespace App\Http\Controllers\Management;

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
        $this->validate($request, $this->registration_item_api->validation_rules);


        $post = $request->all();
        $success = $this->registration_item_api->store($request);

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
        $json = $this->registration_item_api->show($id);
        $data = json_decode($json->content(), true);


        if(empty($data)){
            session()->flash('alert_message', '<h3>お探しのアイテムは見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        return view('management.item.show', ['data' => $data['data']]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request){
        


        $post = $request->all();

        $success = $this->registration_item_api->update($request);

        if($success){
            session()->flash('success_message', '<h3>正常に更新しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');
        }

        return redirect()->back();

    }

}
