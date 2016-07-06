<?php

namespace App\Http\Controllers\Management\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;


class ReturnStaffApiController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {
        $data = Admin::onlyTrashed()->get();
        return response()->json($data);
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
        $validation = Validator::make($request->all(), ['id'   => 'required|numeric']);
        if($validation->fails()) return false;

        $post       = $request->all();
        $id         = $post['id'];

        try{
            Admin::where('id', '=', $id)->restore();
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }
}
