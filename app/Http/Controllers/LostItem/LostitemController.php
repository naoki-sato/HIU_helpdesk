<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\Place;
use App\Models\User;
use App\Models\Student;

class LostitemController extends Controller
{
    private $places;

    public function __construct()
    {
        parent::__construct();
        $this->places = Place::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function getIndex()
    {
        return view('lostitem.index', ['places' => $this->places]);
    }

    /**
     * Json for DataTables Jquery
     *
     * @return Json
     */
    public function getJsonData()
    {
        $data = null;
        $year = \Input::get('year');

        $data = LostItem::withTrashed()
                ->with('place', 'student', 'recieptStaff', 'deliveryStaff')
                ->whereBetween('created_at', 
                    [convertBeginningFiscalYear($year), convertEndFiscalYear($year)])
                ->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return view
     * 
     */
    public function postIndex(Request $request)
    {
        $this->validate($request, $this->validation_rules);

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
            session()->flash('alert_message', '<h3>落し物の新規登録できませんでした。</h3>');
        }
        session()->flash('success_message', '<h3>落し物の新規登録しました。</h3>');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return view
     */
    public function getShow($id)
    {

        $data = LostItem::withTrashed()
                ->with('place', 'student', 'recieptStaff', 'deliveryStaff')
                ->where('id', '=', $id)
                ->get();
        $result = null;

        if (!$data->isEmpty()) {
            $result = ['data' => $data[0]];
        }

        if(!$result){
            session()->flash('alert_message', '<h3>お探しの落し物は見つかりませでした。</h3>');
            return redirect('lost-item');
        }

        return view('lostitem.show', ['data' => $result['data'], 'places' => $this->places]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect
     */
    public function postUpdate(Request $request, $id){


        $this->validate($request, $this->validation_rules);

        $post       = $request->all();
        $item_name  = $post['item_name'];
        $place_id   = $post['place_id'];
        $note       = $post['note'];

        try{
            LostItem::where('id', '=', $id)
                ->update([
                    'lost_item_name' => $item_name,
                    'place_id'       => $place_id,
                    'note'           => $note
                ]);
        } catch(\Exception $e) {
            session()->flash('alert_message', '<h3>更新できませんでした。</h3>');

        }
        session()->flash('success_message', '<h3>正常に更新しました。</h3>');

        return redirect()->back();

    }

    /**
     * SoftDelete the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return redirect
     */
    public function postDelivery(Request $request, $id)
    {
        $this->validate($request, $this->validation_rules);
        // $request['student_id'] = self::convertStudentFromNoToId($request->get('student_no'));

        $post = $request->all();
        $student_id = self::convertStudentFromNoToId($post['student_no']);
        $delivery_staff_id = $post['delivery_staff_id'];


        if(empty($student_id)){
            // TODO
            // 学生テーブルに登録がなかった場合の処理
            // 登録する
            dd('ne-yo');
        }


        // 落し物主と引渡担当者noを更新してソフト削除

        try{
            LostItem::where('id', '=', $id)
                ->update(['student_id' => $student_id, 
                        'delivery_staff_id' => $delivery_staff_id]);
            LostItem::find($id)->delete();
        } catch(\Exception $e) {
            session()->flash('alert_message', '<h3>処理ができませんでした。</h3>');
        }
        
        session()->flash('success_message', '<h3>正常に引渡処理が完了しました。</h3>');


        return redirect('lost-item');
        // return redirect()->route('lost-item.index');
    }


    /**
     * 学生NOからID取得
     *
     * @param  int  $no
     * @return id or null
     */
    private function convertStudentFromNoToId($no){

        $student = Student::where('student_no', $no)->first();

        if(empty($student)){
            return null;
        }

        return $student->id;


    }
}
