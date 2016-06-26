<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use Carbon\Carbon;
use App\Models\Item;

class RegistrationItemApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {
        $data = Item::all();
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
        $validation = Validator::make($request->all(),
                ['item_cd'     => 'required|unique:items,item_cd',
                 'serial_cd'   => 'required',
                 'description' => 'required']);
        if($validation->fails()) return false;

        $post = $request->all();
        $item_cd   = mb_convert_kana($post['item_cd'], 'sa');
        $serial_cd = mb_convert_kana($post['serial_cd'], 'sa');
        $description = $post['description'];

        try{
            $item = new Item;
            $item->item_cd   = $item_cd;
            $item->serial_cd = $serial_cd;
            $item->description = $description;
            $item->save();
        } catch(\PDOException $e) {
            return false;
        }
        return true;
    }

}
