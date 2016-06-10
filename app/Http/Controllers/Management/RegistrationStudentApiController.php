<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Student;

class RegistrationStudentApiController extends Controller
{

    public function __construct()
    {
        parent::__construct();
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

        $post           = $request->all();
        $student_name   = mb_convert_kana($post['student_name'], 'as');
        $student_no     = mb_convert_kana($post['student_no'], 'as');
        $phone_no       = mb_convert_kana($post['phone'], 'as');

        try{
            $student = new Student;
            $student->student_name  = $student_name;
            $student->student_no    = $student_no;
            $student->phone_no      = $phone_no;
            $student->save();
        } catch(\PDOException $e) {
            return false;
        }
        return true;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $student_no (ex. s1312007)
     * @return Json
     */
    public function show($student_no)
    {

        $data = Student::where('student_no', '=', mb_convert_kana($student_no, 'as'))->first();

        if (!$data) return null;

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return success : true, fail : false
     * @note 電話番号のみ更新
     */
    public function update(Request $request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post = $request->all();
        $student_no     = mb_convert_kana($post['student_no'], 'as');
        $phone_no       = mb_convert_kana($post['phone'], 'as');

        $id = Student::where('student_no', '=', $student_no)->first();
        try{
            Student::where('id', '=', $id->id)
                ->update([
                    'phone_no' => $phone,
                ]);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

}
