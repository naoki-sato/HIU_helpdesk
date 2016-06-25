<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\Admin;

class RegistrationStaffApiController extends Controller
{

    public $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                'staff_name' => 'sometimes|required',
                'staff_cd'   => 'sometimes|required|unique:admins,staff_cd',
                'phone_no'=> 'sometimes|required|unique:admins,phone_no|numeric',
                'email' => 'sometimes|required|email|max:255|unique:admins,email',
                'password' => 'sometimes|required|min:6|confirmed',
                'staff_id' => 'sometimes|required',
                'role' => 'sometimes|required|in:admin,manager,staff'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {
        $data = Admin::all();
        return response()->json($data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return success : true, fail : false
    */
    public function store(Request $request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;


        $post = $request->all();
        $staff_name = $post['staff_name'];
        $staff_no   = mb_convert_kana($post['staff_no'], 'sa');
        $password   = $post['password'];
        $role       = 'staff';
        $phone_no   = mb_convert_kana($post['phone_no'], 'sa');
        $email      = $post['email'];

        try{
            $staff = new Admin;
            $staff->name       = $staff_name;
            $staff->staff_no   = $staff_no;
            $staff->password   = bcrypt($password);
            $staff->role       = $role;
            $staff->phone_no   = $phone_no;
            $staff->email      = $email;
            $staff->save();
        } catch(\PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Json
     */
    public function show($id)
    {
        $data = Admin::where('id', '=', $id)
                ->get();
        $result = null;

        if (!$data->isEmpty()) {
            $result = ['data' => $data[0]];
        }

        return response()->json($result);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return success : true, fail : false
     */
    public function update(Request $request)
    {
        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post       = $request->all();
        $role       = $post['role'];
        $id         = $post['id'];

        try{
            Admin::where('id', '=', $id)
                ->update(['role' => $role]);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return success : true, fail : false
     */
    public function destroy($request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        // 落し物主と引渡担当者noを更新してソフト削除
        $post = $request->all();
        $id   = $post['staff_id'];

        try{
            Admin::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }
        
        return true;
    }


}
