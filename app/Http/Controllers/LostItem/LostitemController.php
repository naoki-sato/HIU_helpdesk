<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\Place;
use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Management\User\RegistrationUserApiController;

class LostitemController extends Controller
{
    private $lost_item_api;
    private $registration_user_api;
    private $places;

    public function __construct()
    {
        $this->lost_item_api = new LostItemApiController();
        $this->registration_user_api = new RegistrationUserApiController();
        $this->places = Place::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index()
    {
        return view('lostitem.index', ['places' => $this->places]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return view
     * 
     */
    public function store(Request $request)
    {
        $this->validate($request, 
            ['item_name'         => 'required|string',
             'place_id'          => 'required|numeric',
             'staff_id'          => 'required|numeric'
            ]);
        $post = $request->all();
        $success = $this->lost_item_api->store($request);

        if($success){
            session()->flash('success_message', '<h3>落し物の新規登録しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>落し物の新規登録できませんでした。</h3>');
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

        $json = $this->lost_item_api->show($id);
        $data = json_decode($json->content(), true);

        if(empty($data)){
            session()->flash('alert_message', '<h3>お探しの落し物は見つかりませでした。</h3>');
            return view('errors.error_msg');
        }

        // 引渡の未・済でテンプレートわけ
        if($data['data']['deleted_at']){
            return view('lostitem.finished', ['data' => $data['data'], 'places' => $this->places]);
        }

        return view('lostitem.show', ['data' => $data['data'], 'places' => $this->places]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function update(Request $request, $id){

        $this->validate($request, [
             'item_name'         => 'required|string',
             'place_id'          => 'required|numeric']);
        
        $success = $this->lost_item_api->update($request, $id);

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
    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
                'delivery_staff_id' => 'required|numeric',
                'user_cd'           => 'required|numeric',
                'user_name'         => 'required|string',
                'phone_no'          => 'required|numeric']);

        $is_empty = $this->registration_user_api->show($request->get('user_cd'));

        // DBに登録されていなければ，新規登録。 あれば情報を更新
        if($is_empty){
            $this->registration_user_api->update($request);
        }else{
            $this->registration_user_api->store($request);
        }

        $request['user_cd'] = $request->get('user_cd');
        $success = $this->lost_item_api->destroy($request, $id);

        if($success){
            session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }


        return redirect()->route('lost-item.index');
    }



}
