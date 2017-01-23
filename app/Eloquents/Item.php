<?php

/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * createメソッド実行時に、入力を禁止するカラムの指定
     *
     * @var array
     */
    protected $guarded = ['id'];
}
