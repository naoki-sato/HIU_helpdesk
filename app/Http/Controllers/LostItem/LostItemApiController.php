<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\LostItem;
use App\Models\Place;


class LostItemApiController extends Controller
{
    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                // store + update
                'item_name'         => 'sometimes|required|string',
                'place_id'          => 'sometimes|required|numeric',
                'staff_id'          => 'sometimes|required|numeric',
                // destroy
                'delivery_staff_id' => 'sometimes|required|numeric',
                'student_id'        => 'sometimes|required|numeric'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {

        $data = null;
        $year = \Input::get('year');

        // (1)だとソフトデリートされたスタッフのところが空白になるため
        // (2)を使用する
        // (1)
        // $data = LostItem::withTrashed()
        //         ->with('place', 'student', 'recieptStaff', 'deliveryStaff')
        //         ->whereBetween('created_at', 
        //             [convertBeginningFiscalYear($year), convertEndFiscalYear($year)])
        //         ->get();

        // (2)
        $data = LostItem::withTrashed()
            ->leftJoin('users', 'users.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('students', 'students.id', '=', 'lost_items.student_id')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('users as A', 'A.id', '=', 'lost_items.delivery_staff_id')
            ->select('lost_items.id AS id',
                    'lost_items.created_at AS created_at',
                    'lost_items.lost_item_name AS lost_item_name',
                    'places.room_name AS room_name',
                    'users.name AS reciept_staff_name',
                    'lost_items.note AS note',
                    'lost_items.deleted_at AS deleted_at',
                    'A.name AS delivery_staff_name',
                    'students.student_name AS student_name'
                    )
            ->whereBetween('lost_items.created_at', 
                    [convertBeginningFiscalYear($year), convertEndFiscalYear($year)])
            ->get();

        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return success : true, fail : false
     * 
     */
    public function store(Request $request)
    {
        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post      = $request->all();
        $note      = $post['note'];
        $place     = $post['place_id'];
        $staff_id  = $post['staff_id'];
        $item_name = $post['item_name'];

        try{
            $item = new LostItem;
            $item->lost_item_name = $item_name;
            $item->reciept_staff_id = $staff_id;
            $item->place_id = $place;
            $item->note = $note;
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
        // (1)だとソフトデリートされたスタッフのところが空白になるため
        // (2)を使用する
        // (1)
        // $data = LostItem::withTrashed()
        //         ->with('place', 'student', 'recieptStaff', 'deliveryStaff')
        //         ->where('id', '=', $id)
        //         ->get();
        // $result = null;

        // if (!$data->isEmpty()) {
        //     $result = ['data' => $data[0]];
        // }

        // (2)
        $data = LostItem::withTrashed()
            ->where('lost_items.id', '=', $id)
            ->leftJoin('users', 'users.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('students', 'students.id', '=', 'lost_items.student_id')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('users as A', 'A.id', '=', 'lost_items.delivery_staff_id')
            ->select('lost_items.id AS id',
                    'lost_items.created_at AS created_at',
                    'lost_items.lost_item_name AS lost_item_name',
                    'places.id AS place_id',
                    'users.name AS reciept_staff_name',
                    'lost_items.note AS note',
                    'lost_items.deleted_at AS deleted_at',
                    'A.name AS delivery_staff_name',
                    'students.student_no AS student_no',
                    'students.phone_no AS student_phone_no',
                    'students.student_name AS student_name'
                    )
            ->get();

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
    public function update(Request $request, $id)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post       = $request->all();
        $item_name  = $post['item_name'];
        $place_id   = $post['place_id'];
        $note       = $post['note'];

        try{
            LostItem::where('id', '=', $id)
                ->update([
                    'lost_item_name' => $item_name,
                    'place_id' => $place_id,
                    'note' => $note
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
    public function destroy($request, $id)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        // 落し物主と引渡担当者noを更新してソフト削除
        $post = $request->all();
        $student_id = $post['student_id'];
        $delivery_staff_id = $post['delivery_staff_id'];

        try{
            LostItem::where('id', '=', $id)
                ->update(['student_id' => $student_id, 
                        'delivery_staff_id' => $delivery_staff_id]);
            LostItem::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }
        
        return true;
    }

}
