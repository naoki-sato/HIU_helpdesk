<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\LostItem;
use App\Models\Place;
use Storage;
use Image;
use Input;


class LostItemApiController extends Controller
{


    public function __construct(){

        // 指定されたディレクトリは存在するか確認、無ければ作成
        if(!Storage::disk('local')->exists('images_store/lost-item')) {
            Storage::makeDirectory('images_store/lost-item', 0755);
        }
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
            ->select('lost_items.id AS id',
                    'lost_items.created_at AS created_at',
                    'lost_items.lost_item_name AS lost_item_name',
                    'places.room_name AS room_name',
                    'admins.name AS reciept_staff_name',
                    'lost_items.note AS note',
                    'lost_items.deleted_at AS deleted_at',
                    'A.name AS delivery_staff_name',
                    'users.user_name AS user_name',
                    'lost_items.file_name AS file_name'
                    )
            ->leftJoin('admins', 'admins.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('users', 'users.user_cd', '=', 'lost_items.user_cd')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('admins as A', 'A.id', '=', 'lost_items.delivery_staff_id')
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
        $validation = Validator::make($request->all(), 
            ['item_name'   => 'required|string',
             'place_id'    => 'required|numeric',
             'staff_id'    => 'required|numeric',
             'file_input'  => 'image|mimes:jpeg,jpg,png,gif'
            ]);

        if($validation->fails()) return false;


        $post      = $request->all();
        $note      = $post['note'];
        $place     = $post['place_id'];
        $staff_id  = $post['staff_id'];
        $item_name = $post['item_name'];
        $name      = 'no_image';

        if(Input::hasFile('file_input')){
            $image     = $post['file_input'];
            // ファイル名をランダムに生成
            $name = md5(sha1(uniqid(mt_rand(), true))) . '.' . $image->getClientOriginalExtension();
            $send_image_path = storage_path('app/images_store/lost-item/') . $name;
            self::saveImage($name ,$image);
        }


        try{
            $item = new LostItem;
            $item->lost_item_name   = $item_name;
            $item->reciept_staff_id = $staff_id;
            $item->place_id         = $place;
            $item->note             = $note;
            $item->file_name        = $name;
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
            ->leftJoin('admins', 'admins.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('users', 'users.user_cd', '=', 'lost_items.user_cd')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('admins as A', 'A.id', '=', 'lost_items.delivery_staff_id')
            ->select('lost_items.id AS id',
                    'lost_items.created_at AS created_at',
                    'lost_items.lost_item_name AS lost_item_name',
                    'places.id AS place_id',
                    'admins.name AS reciept_staff_name',
                    'lost_items.note AS note',
                    'lost_items.deleted_at AS deleted_at',
                    'A.name AS delivery_staff_name',
                    'users.user_cd AS user_no',
                    'users.phone_no AS student_phone_no',
                    'users.user_name AS user_name',
                    'lost_items.file_name AS file_name'
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
        $validation = Validator::make($request->all(), 
            ['item_name'   => 'required|string',
             'place_id'    => 'required|numeric',
             'file_input'  => 'image|mimes:jpeg,jpg,png,gif'
            ]);
        if($validation->fails()) return false;

        $post       = $request->all();
        $item_name  = $post['item_name'];
        $place_id   = $post['place_id'];
        $note       = $post['note'];
        $name       = 'no_image.jpg';
        $update     = [
                       'lost_item_name' => $item_name,
                       'place_id'       => $place_id,
                       'note'           => $note
                       ];

        if(Input::hasFile('file_input')){
            $image     = $post['file_input'];
            // ファイル名をランダムに生成
            $name = md5(sha1(uniqid(mt_rand(), true))) . '.' . $image->getClientOriginalExtension();
            $send_image_path = storage_path('app/images_store/lost-item/') . $name;
            self::saveImage($name ,$image);
            $update += ['file_name'      => $name];
        }

        try{
            LostItem::where('id', '=', $id)
                ->update($update);
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
        $validation = Validator::make($request->all(), 
                ['delivery_staff_id' => 'required|numeric',
                 'user_cd'           => 'required|numeric']);
        if($validation->fails()) return false;

        // 落し物主と引渡担当者noを更新してソフト削除
        $post = $request->all();
        $user_cd = $post['user_cd'];
        $delivery_staff_id = $post['delivery_staff_id'];

        try{
            LostItem::where('id', '=', $id)
                ->update(['user_cd' => $user_cd, 
                        'delivery_staff_id' => $delivery_staff_id]);
            LostItem::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }
        
        return true;
    }

    /**
     * 画像をstorageに保存する
     * 画像はwidth300に変換
     * @param $name
     * @param $image
     */
    private function saveImage($name, $image){


        $image_path  = storage_path('app/images_store/lost-item/') . $name;

        $img = Image::make($image);

        $img->orientate()
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();})
            ->save($image_path);

    }

}
