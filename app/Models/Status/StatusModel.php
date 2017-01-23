<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Models\Status;

use App\Eloquents\Item;
use App\Eloquents\Status;
use App\Models\User\RegistrationModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StatusModel extends Model
{

    // ユーザモデル
    private $registration_user_model;


    /**
     * StatusModel constructor.
     */
    public function __construct()
    {
        $this->registration_user_model = new RegistrationModel();
    }

    /**
     * 貸出ページのテーブル用データの取得
     */
    public function getIndexTableData()
    {
        $result = Status::select(
            'statuses.id AS id',
            'statuses.item_cd AS item_cd',
            'items.description AS description',
            'users.user_cd AS user_cd',
            'users.user_name AS user_name',
            'users.phone_no AS phone_no',
            'admins.name AS lended_staff_name',
            'statuses.created_at AS created_at',
            'statuses.comment AS comment'
            )
            ->leftJoin('admins', 'admins.id', '=', 'statuses.lended_staff_id')
            ->leftJoin('items', 'items.item_cd', '=', 'statuses.item_cd')
            ->leftJoin('users', 'users.user_cd', '=', 'statuses.lended_user_cd')
            ->whereNull('statuses.deleted_at')
            ->get();

        return $result;
    }


    /**
     * 貸出アイテムを「貸出済み」の処理をする
     *
     * @param $user_name
     * @param $user_cd
     * @param $phone_no
     * @param $rental_items
     * @param $staff_id
     * @param $comment
     * @return bool
     */
    public function rentalItems($user_name, $user_cd, $phone_no, $rental_items, $staff_id, $comment)
    {

        // DBにマルチインサートするための，まとめた値
        $merge_value = null;

        // DBにユーザ情報が登録されていなければ新規登録，登録されていればアップデート
        $this->registration_user_model->replace($user_name, $user_cd, $phone_no);

        // アイテムを貸し出すことができるか
        $is_rentable = self::isRentableItems($rental_items);

        if (!$is_rentable) return false;


        // マルチインサートするために，まとめる
        foreach ($rental_items as $value) {
            $merge_value[] =
                [
                    'lended_staff_id'   => $staff_id,
                    'lended_user_cd'    => $user_cd,
                    'item_cd'           => $value,
                    'comment'           => $comment,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ];
        }

        try {
            // インサート
            Status::insert($merge_value);

        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * 貸出済みアイテムの返却処理をする
     *
     * @param $return_items
     * @param $staff_id
     * @return bool
     */
    public function returnItems($return_items, $staff_id)
    {
        // アイテムを返却することができるか
        $is_returnable = self::isReturnableItems($return_items);

        if (!$is_returnable) return false;

        // 引渡担当者を更新してソフト削除
        foreach ($return_items as $key => $value) {
            try {
                Status::where('item_cd', '=', $value)->update(['returned_staff_id' => $staff_id]);
                Status::where('item_cd', '=', $value)->delete();
            } catch(\Exception $e) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * 貸出中のアイテムが混じっていないか、
     * DBに貸出アイテムとして登録されているか
     * の２点をチェック
     * 1つでも混じっていたらfalse
     *
     * @param $items
     * @return bool
     */
    private function isRentableItems($items)
    {

        // TODO ここなんとか，スマートにできそう
        foreach ($items as $key => $value) {
            // 貸出中のアイテムが混じっていないか
            $is_check = Status::where('item_cd', '=', $value)->first();
            if ($is_check) return false;

            // DBに貸出アイテムとして登録されているか
            $is_check = Item::where('item_cd', '=', $value)->first();
            if (empty($is_check)) return false;
        }

        return $items;
    }

    /**
     * 貸出していないアイテムが混じっていないか、
     * DBに貸出アイテムとして登録されているか
     * の２点をチェック
     * 1つでも混じっていたらfalse
     *
     * @param $items
     * @return bool
     */
    private function isReturnableItems($items)
    {

        // TODO ここなんとか，スマートにできそう
        foreach ($items as $key => $value) {
            // 貸出中のアイテムが混じっていないか
            $is_check = Status::where('item_cd', '=', $value)->first();
            if (!$is_check) return false;

            // DBに貸出アイテムとして登録されているか
            $is_check = Item::where('item_cd', '=', $value)->first();
            if (empty($is_check)) return false;
        }

        return $items;
    }


}
