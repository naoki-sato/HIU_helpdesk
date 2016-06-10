<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\Place;
use App\Models\User;
use App\Models\Student;

class LostitemController extends Controller
{
    private $api;
    private $places;

    public function __construct()
    {
        parent::__construct();
        $this->api = new LostItemApiController();
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

        $this->validate($request, $this->validation_rules);

        $post = $request->all();
        $success = $this->api->store($request);

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
        $json = $this->api->show($id);
        $data = json_decode($json->content(), true);

        if(empty($data)){
            session()->flash('alert_message', '<h3>お探しの落し物は見つかりませでした。</h3>');
            return view('errors.error_msg');
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

        $this->validate($request, $this->validation_rules);
        
        $success = $this->api->update($request, $id);

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
        $this->validate($request, $this->validation_rules);
        $request['student_id'] = self::convertStudentFromNoToId($request->get('student_no'));



        if(empty($student_id)){
            // TODO
            // 学生テーブルに登録がなかった場合の処理
            // 登録する
        }


        $success = $this->api->destroy($request, $id);
        if($success){
            session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');
        }else{
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }


        return redirect()->route('lost-item.index');
    }


    /**
     * 学生NOからID取得
     *
     * @param  int  $no
     * @return id or null
     */
    private function convertStudentFromNoToId($no){

        $student = Student::where('student_no', $no)->first();

        if(empty($student)){
            return null;
        }

        return $student->id;


    }
}
