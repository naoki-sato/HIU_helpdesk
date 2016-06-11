<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\User;

class RegistrationStaffApiController extends Controller
{

     private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                'start_number' => 'sometimes|required|numeric',
                'end_number'   => 'sometimes|required|numeric',
                'year'         => 'sometimes|required|numeric'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {

        $data = User::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_rules);
        $post = $request->all();
        $staff_name = $post['staff_name'];
        $staff_no   = mb_convert_kana($post['staff_no'], 'sa');
        $password   = $post['password'];
        $role       = 'staff';
        $phone_no   = mb_convert_kana($post['phone_no'], 'sa');
        $email      = $staff_no . '@s.do-johodai.ac.jp';

        try{
            $staff = new User;
            $staff->name       = $staff_name;
            $staff->staff_no   = $staff_no;
            $staff->password   = bcrypt($password);
            $staff->role       = $role;
            $staff->phone_no   = $phone_no;
            $staff->email      = $email;
            $staff->save();
        } catch(\PDOException $e) {
            session()->flash('alert_message', '<h3>スタッフを登録できませんでした。</h3>');
            return redirect()->back();
        }
        session()->flash('success_message', '<h3>スタッフを登録しました。</h3>');
        return redirect()->back();
    }
}
