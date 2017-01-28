<?php

namespace App\Models\Management\Shift;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Eloquents\Shift;
use Input;
use Image;
use Carbon\Carbon;

class ShiftModel extends Model
{

    private $save_path;

    /**
     * ShiftModel constructor.
     */
    public function __construct()
    {
        $this->save_path = 'images_store/shift_table/';

        // 指定されたディレクトリは存在するか確認、無ければ作成
        if(!Storage::disk('public')->exists($this->save_path)) {
            Storage::makeDirectory($this->save_path, 0755);
        }
    }


    /**
     * DBから一番新しいシフト表のファイルパスを取得
     *
     * @return mixed
     */
    public function getNowImagePath()
    {

        // ファイル名を取得
        $file_name = Shift::select('file_name')->orderBy('id', 'DESC')->first();
        $file_name = $file_name['file_name'];

        // ファイルが存在している場合
        if (Storage::disk('public')->exists($this->save_path . $file_name)) {

            return $this->save_path . $file_name;

        } else {

            return url('images/noimage.jpg');
        }

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

        $image_path  = storage_path('app/public/images_store/shift_table/') . $name;

        $img = Image::make($image);

        $img->orientate()->save($image_path);

    }
}
