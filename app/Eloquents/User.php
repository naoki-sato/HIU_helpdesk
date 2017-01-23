<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * モデルに関連付けるデータベースのテーブルを指定
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * createメソッド実行時に、入力を禁止するカラムの指定
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * 生徒から落し物を取得
     */
    public function lostitem()
    {
        return $this->hasMany('App\Models\LostItem');
    }
}
