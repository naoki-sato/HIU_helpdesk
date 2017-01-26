<?php

/**
 * @version 2017/01/26
 * @author  naoki.s 1312007
 */

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /**
     * モデルに関連付けるデータベースのテーブルを指定
     *
     * @var string
     */
    protected $table = 'shift_history';
}
