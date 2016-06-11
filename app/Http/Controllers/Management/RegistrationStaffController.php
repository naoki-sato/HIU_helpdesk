<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

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
        return view('management.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
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


        dd($data);

        // TODO::ここのviewを作る
        // return view('lostitem.show', ['data' => $data['data'], 'places' => $this->places]);
    }


}
