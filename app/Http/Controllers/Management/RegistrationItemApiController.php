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
    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                'item_code'     => 'sometimes|required|unique:items,item_code',
                'serial_code'   => 'sometimes|required',
                'description'   => 'sometimes|required'];
    }


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
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post = $request->all();
        $item_code   = mb_convert_kana($post['item_code'], 'sa');
        $serial_code = mb_convert_kana($post['serial_code'], 'sa');
        $description = $post['description'];

        try{
            $item = new Item;
            $item->item_code   = $item_code;
            $item->serial_code = $serial_code;
            $item->description = $description;
            $item->save();
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
        $data = Item::where('id', '=', $id)
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

        $post = $request->all();
        $id          = $post['id'];
        $item_code   = mb_convert_kana($post['item_code'], 'sa');
        $serial_code = mb_convert_kana($post['serial_code'], 'sa');
        $description = $post['description'];

        try{
            Item::where('id', '=', $id)
                ->update(['item_code'   => $item_code,
                          'serial_code' => $serial_code,
                          'description' => $description]);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

}
