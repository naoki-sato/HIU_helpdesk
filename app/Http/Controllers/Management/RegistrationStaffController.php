<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;


class RegistrationStaffController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $this->validate($request, $this->validation_rules);
        $post = $request->all();


        $staff_name = $post['staff_name'];
        $staff_no   = $post['staff_no'];
        $password   = $post['password'];
        $role       = $post['role'];
        $phone_no   = $post['phone_no'];
        $email      = $post['staff_no'] . '@s.do-johodai.ac.jp';

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
