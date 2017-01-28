<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Models\LostItem;

use App\Eloquents\LostItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Storage;
use Image;
use Input;

class LostItemModel extends Model
{

    private $save_path;

    /**
     * LostItemModel constructor.
     */
    public function __construct()
    {

        $this->save_path = 'images_store/lost-item/';
        // 指定されたディレクトリは存在するか確認、無ければ作成
        // 指定されたディレクトリは存在するか確認、無ければ作成
        if(!Storage::disk('public')->exists($this->save_path)) {
            Storage::makeDirectory($this->save_path, 0755);
        }

    }


    /**
     * 落し物の一覧を取得する
     */
    public function getIndexTableData()
    {
        // 落し物一覧
        $lost_items = null;
        // 年度
        $year       = Input::get('year');

        // 指定された年度の落し物全てのデータを取得する（指定がない場合は，現在年度）
        $lost_items = LostItem::withTrashed()
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


        return $lost_items;
    }


    /**
     * 落し物の登録をする
     *
     * @param Request $request
     * @return bool
     */
    public function insertLostItem(Request $request)
    {

        $post      = $request->all();    // ポストされた全データ
        $note      = $post['note'];      // 備考
        $place     = $post['place_id'];  // 落とした場所
        $staff_id  = $post['staff_id'];  // 引受担当
        $item_name = $post['item_name']; // 落し物名
        $file_name = 'no_image.jpg';     // 落し物画像


        try {

            // 画像がポストされていた場合
            if(Input::hasFile('file_input')){

                $image     = $post['file_input'];
                // ファイル名をランダムに生成
                $file_name = md5(sha1(uniqid(mt_rand(), true))) . '.' . $image->getClientOriginalExtension();
                // 画像をストレージに保存
                self::saveImage($file_name ,$image);

            }

            // DBにインサート
            LostItem::insert(
                [
                    'lost_item_name'    => $item_name,
                    'reciept_staff_id'  => $staff_id,
                    'place_id'          => $place,
                    'note'              => $note,
                    'file_name'         => $file_name,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);


        } catch(\PDOException $e) {
            return false;
        }

        return true;
    }


    /**
     * 落し物の詳細を取得
     *
     * @param $id
     * @return null
     */
    public function showLostItem($id)
    {
        // 落し物情報
        $item_info = null;

        // 指定された落し物情報を取得
        $item = LostItem::withTrashed()
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

        // もし該当する情報がある場合
        if (!$item->isEmpty()) {
            $item_info = $item[0];
        }

        return $item_info;
    }


    /**
     * 落し物の情報をアップデートする
     *
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function updateLostItem(Request $request, $id)
    {

        $post       = $request->all();
        $item_name  = $post['item_name'];
        $place_id   = $post['place_id'];
        $note       = $post['note'];
        $image_name = 'no_image.jpg';
        $update     = [
            'lost_item_name' => $item_name,
            'place_id'       => $place_id,
            'note'           => $note
        ];

        try {
            // 画像がポストされている場合
            if(Input::hasFile('file_input')){

                $image     = $post['file_input'];
                // ファイル名をランダムに生成
                $image_name = md5(sha1(uniqid(mt_rand(), true))) . '.' . $image->getClientOriginalExtension();
                // 画像をストレージに保存
                self::saveImage($image_name ,$image);
                $update += ['file_name' => $image_name];
            }

            // UPDATE
            LostItem::where('id', '=', $id)->update($update);

        } catch(\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * 落し物の引渡の処理をする
     *
     * @param $id
     * @param $user_cd
     * @param $staff_id
     * @return bool
     */
    public function destroyLostItem($id, $user_cd, $staff_id)
    {

        // 落し物主と引渡担当者noを更新してソフト削除
        try {
            LostItem::where('id', '=', $id)
                ->update(
                    [
                        'user_cd' => $user_cd,
                        'delivery_staff_id' => $staff_id
                    ]);

            LostItem::find($id)->delete();
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * 画像をstorageに保存する
     * 画像はwidth500に変換
     * @param $name
     * @param $image
     */
    private function saveImage($name, $image){

        $image_path  = storage_path('app/public/images_store/lost-item/') . $name;

        $img = Image::make($image);

        $img->orientate()
            ->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();})
            ->save($image_path);

    }

}
