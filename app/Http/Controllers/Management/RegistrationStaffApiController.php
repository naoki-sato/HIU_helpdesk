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
                'staff_name' => 'sometimes|required|unique:users,name',
                'staff_no'   => 'sometimes|required|unique:users,staff_no',
                'phone_no'=> 'sometimes|required',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'sometimes|required|min:6|confirmed',
                'staff_id' => 'sometimes|required',];
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
            $staff = new User;
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
        $data = User::where('id', '=', $id)
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
        $validation = Validator::make($request->all(), 
            ['email' => 'required|email|max:255',
             'role' => 'sometimes|required|in:admin,manager,staff']);
        if($validation->fails()) return false;

        $post       = $request->all();
        $phone_no   = $post['phone_no'];
        $email      = $post['email']; //
        $role       = $post['role'];

        $id = $post['id'];

        try{
            User::where('id', '=', $id)
                ->update([
                    'email'     => $email,
                    'phone_no'  => $phone_no,
                    'role'      => $role
                ]);
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
            User::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }
        
        return true;
    }


}
