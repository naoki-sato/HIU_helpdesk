<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Input;

class RegistrationUserController extends Controller
{
    
    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                'user_name' => 'sometimes|required',
                'user_cd'   => 'sometimes|required|unique:users,user_cd'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

        $data = User::all();
        return view('management.user.index', ['data' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request){

        $this->validate($request, $this->validation_rules);

        $post      = $request->all();
        $user_name = mb_convert_kana($post['user_name'], 'as');
        $user_cd   = mb_convert_kana($post['user_cd'], 'as');

        try{
            $user = new User;
            $user->user_name  = $user_name;
            $user->user_cd    = $user_cd;
            $user->save();
        } catch(\PDOException $e) {
            session()->flash('alert_message', '<h3>登録できませんでした。</h3>');
        }

        session()->flash('success_message', '<h3>正常に登録しました。</h3>');
        return redirect()->back();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postImport(Request $request)
    {

        $this->validate($request, ['file_input'=> 'required']);

        if(Input::hasFile('file_input')){
            $path = Input::file('file_input')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();

            if(!empty($data) && $data->count()){

                User::truncate();

                foreach ($data as $key => $value) {
                    try{
                        $user = new User;
                        $user->user_cd      = $value->user_cd;
                        $user->user_name    = $value->user_name;
                        $user->save();
                    } catch(\PDOException $e) {

                        session()->flash('alert_message', '<h3>[user_cd]が重複しているか，形式が間違っているため，Importできませんでした。</h3>');
                        return back();
                    }
                }
            }
        }
        session()->flash('success_message', '<h3>正常にImportできました。</h3>');
        return back();
    }

    public function postExample(Request $request){

        $users = [['user_cd', 'user_name'], ['s1234567', '情報 太郎'], ['s1312007', '佐藤 直己']];

        Excel::create('user_import_example', function($excel) use($users) {
            $excel->sheet('1', function($sheet) use($users){
                $sheet->fromArray($users, null, 'A1', false, false);
            });
         })->export('csv');
    }


}
