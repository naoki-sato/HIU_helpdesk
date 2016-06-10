<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LostItem extends Model
{
    use SoftDeletes;

    /**
     * 日付により変更を起こすべき属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'lost_items';

    /**
     * 落し物に関連する場所レコードを取得
     */
    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    /**
     * 落し物に関連する受取スタッフレコードを取得
     */
    public function recieptStaff()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 落し物に関連する引渡スタッフレコードを取得
     */
    public function deliveryStaff()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 落し物に関連する落し物主レコードを取得
     */
    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }
}
