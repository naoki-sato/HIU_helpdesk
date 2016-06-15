<?php

namespace App\Http\Controllers\Lending;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use Carbon\Carbon;
use App\Models\Student;
use App\Models\Status;
use App\Models\Item;
use App\Http\Controllers\Management\RegistrationStudentApiController;

class StatusApiController extends Controller
{
    
    private $validation_rules;
    private $registration_student_api;

    public function __construct()
    {
        $this->validation_rules = [
                'student_name' => 'sometimes|required',
                'student_no'   => 'sometimes|required',
                'phone_no'=> 'sometimes|required'];

        $this->registration_student_api = new RegistrationStudentApiController();
    }


    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {
        $data = Status::select(
                    'statuses.id AS id',
                    'users.name AS lended_staff_name',
                    'students.student_name AS student_name',
                    'students.student_no AS student_no',
                    'students.phone_no AS phone_no',
                    'statuses.item_code AS item_code',
                    'statuses.comment AS comment',
                    'statuses.created_at AS created_at',
                    'items.description AS description'
                    )
                ->leftJoin('users', 'users.id', '=', 'statuses.lended_staff_id')
                ->leftJoin('items', 'items.item_code', '=', 'statuses.item_code')
                ->leftJoin('students', 'students.id', '=', 'statuses.lended_student_id')
                ->get();

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
        $student_name = $post['student_name'];
        $student_no   = mb_convert_kana($post['student_no'], 'sa');
        $phone_no = $post['phone'];
        $lend_items = $post['lend_item'];
        $student_id = convertStudentFromNoToId($student_no);
        $lended_staff_id = $request->user()['id'];
        $comment = $post['note'];

        if(!$student_id){
            $this->registration_student_api->store($request);
            $student_id = convertStudentFromNoToId($student_no);
        }

        $checked_list = self::checkLendedItems($lend_items);

        if(!$checked_list){
            return false;
        }

        foreach ($checked_list as $key => $value) {
            try{
                $status = new Status;

                $status->lended_staff_id   = $lended_staff_id;
                $status->lended_student_id = $student_id;
                $status->item_code   = $value;
                $status->comment       = $comment;
                $status->save();
            } catch(\PDOException $e) {
                return false;
            }
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
        // $data = User::where('id', '=', $id)
        //         ->get();
        // $result = null;

        // if (!$data->isEmpty()) {
        //     $result = ['data' => $data[0]];
        // }

        // return response()->json($result);
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
        // // バリデーションに引っかかったら, false
        // $validation = Validator::make($request->all(), $this->validation_rules);
        // if($validation->fails()) return false;

        // $post       = $request->all();
        // $role       = $post['role'];
        // $id         = $post['id'];

        // try{
        //     User::where('id', '=', $id)
        //         ->update(['role' => $role]);
        // } catch(\Exception $e) {
        //     return false;
        // }
        // return true;
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

    //     // バリデーションに引っかかったら, false
    //     $validation = Validator::make($request->all(), $this->validation_rules);
    //     if($validation->fails()) return false;

    //     // 落し物主と引渡担当者noを更新してソフト削除
    //     $post = $request->all();
    //     $id   = $post['staff_id'];

    //     try{
    //         User::find($id)->delete();
    //     } catch(\Exception $e) {
    //         return false;
    //     }
        
    //     return true;
    }

    /*
     * 貸出中のアイテムが混じっていないか、
     * DBに貸出アイテムとして登録されているか
     * の２点をチェック
     * 1つでも混じっていたらfalse
     */
    private function checkLendedItems($items)
    {

        $items = array_filter($items);

        foreach ($items as $key => $value) {
            // 貸出中のアイテムが混じっていないか
            $is_check = Status::where('item_code', '=', $value)->first();
            if($is_check) return false;

            // DBに貸出アイテムとして登録されているか
            $is_check = Item::where('item_code', '=', $value)->first();
            if(empty($is_check)) return false;
        }
        return $items;

    }
}
