<?php

/**
 * @version 2017/01/22
 * @author  naoki.s 1312007
 */

namespace App\Models\Management\Item;

use App\Eloquents\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{

    /**
     * 貸出用アイテム一覧の取得
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getIndexTableData()
    {
        return Item::all();
    }


    /**
     * 貸出用アイテムをDBに登録
     *
     * @param $item_cd
     * @param $serial_cd
     * @param $description
     * @return bool
     */
    public function registrationItem($item_cd, $serial_cd, $description)
    {
        // 全角英字を半角英字に変換
        $item_cd   = mb_convert_kana($item_cd, 'sa');
        $serial_cd = mb_convert_kana($serial_cd, 'sa');

        try{
            Item::updateOrCreate(
                ['item_cd'   => $item_cd],
                [
                    'item_cd'     => $item_cd,
                    'serial_cd'   => $serial_cd,
                    'description' => $description,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]);
        } catch(\PDOException $e) {
            return false;
        }
        return true;
    }


}
