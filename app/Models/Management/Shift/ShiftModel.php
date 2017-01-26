<?php

namespace App\Models\Management\Shift;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;
use Storage;
use App\Eloquents\Shift;
use Input;
use Image;
use Carbon\Carbon;

class ShiftModel extends Model
{

    /**
     * ShiftModel constructor.
     */
    public function __construct()
    {
        // 指定されたディレクトリは存在するか確認、無ければ作成
        if(!Storage::disk('local')->exists('images_store/shift_table')) {
            Storage::makeDirectory('images_store/shift_table', 0755);
        }
    }


    /**
     * DBから一番新しいシフト表のファイル情報を取得
     *
     * @return mixed
     */
    public function getNowImageName()
    {
        // 新しいシフトを取得
        return Shift::select('file_name')->orderBy('id', 'DESC')->first();
    }


    /**
     * DBとStorageに画像を保存
     *
     * @param Request $request
     * @return bool
     */
    public function insertImageName(Request $request)
    {

        $post      = $request->all();    // ポストされた全データ
        $file_name = 'no_image';         // 落し物画像

        try {

            // 画像がポストされていた場合
            if(Input::hasFile('file_input')){

                $image     = $post['file_input'];
                // ファイル名をランダムに生成
                $file_name = md5(sha1(uniqid(mt_rand(), true))) . '.' . $image->getClientOriginalExtension();
                // 画像をストレージに保存
                self::saveImage($file_name ,$image);


                // DBにインサート
                Shift::insert(
                    [
                        'file_name'         => $file_name,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    ]);
            } else {
                return false;
            }

        } catch(\PDOException $e) {
            return false;
        }

        return true;
    }


    /**
     * 画像をstorageに保存する
     * @param $name
     * @param $image
     */
    private function saveImage($name, $image){

        $image_path  = storage_path('app/images_store/shift_table/') . $name;

        $img = Image::make($image);

        $img->orientate()->save($image_path);

    }
}
