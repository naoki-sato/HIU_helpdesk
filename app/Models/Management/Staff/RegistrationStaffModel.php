<?php

namespace App\Models\Management\Staff;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Eloquents\Admin;
use Illuminate\Database\Eloquent\Model;

class RegistrationStaffModel extends Model
{
    //



    public function getStaffListData()
    {

        return Admin::all();

    }


    public function insertStaff(Request $request)
    {

        $post       = $request->all();
        $staff_name = $post['staff_name'];
        $staff_cd   = mb_convert_kana($post['staff_cd'], 'sa');
        $password   = bcrypt($post['password']);
        $role       = 'staff';
        $phone_no   = mb_convert_kana($post['phone_no'], 'sa');
        $email      = $post['email'];

        try {

            Admin::insert(
                [
                    'name'      => $staff_name,
                    'staff_cd'  => $staff_cd,
                    'password'  => $password,
                    'role'      => $role,
                    'phone_no'  => $phone_no,
                    'email'     =>$email,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );

        } catch(\PDOException $e) {
            return false;
        }

        return true;
    }



    public function show($id)
    {

        $data = Admin::where('id', '=', $id)->get();

        $result = null;

        if (!$data->isEmpty()) {
            $result = $data[0];
        }

        return $result;

    }


    public function updateRole($id, $role)
    {

        try{

            Admin::where('id', '=', $id)
                ->update(['role' => $role]);

        } catch(\Exception $e) {
            return false;
        }

        return true;
    }


    public function deleteStaff($id)
    {
        try{
            Admin::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }

        return true;

    }



}
