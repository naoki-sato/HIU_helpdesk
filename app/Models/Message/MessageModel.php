<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Models\Message;

use App\Eloquents\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{

    /**
     * 30日以内に掲示板に投稿されたメッセージ一覧を取得する。
     */
    public function getMessage()
    {

        // メッセージs
        $messages = null;

        $messages = Message::select(
            'messages.description AS description',
            'messages.created_at AS created_at',
            'admins.name AS name')
            ->leftJoin('admins', 'admins.id', '=', 'messages.staff_id')
            ->where('messages.created_at', '>', Carbon::now()->subDay(30))
            ->get();

        return $messages;
    }


    /**
     * 掲示板に投稿されたメッセージをDBにインサートする。
     *
     * @param $info_mes
     * @param $staff_id
     * @return bool
     */
    public function insertMessage($info_mes, $staff_id)
    {
        try{
            Message::insert(
                [
                    'description'   => $info_mes,
                    'staff_id'      => $staff_id,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]
            );

        } catch(\PDOException $e) {
            return false;
        }

        return true;
    }

}
