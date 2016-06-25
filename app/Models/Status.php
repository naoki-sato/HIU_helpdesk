<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    /**
     * 日付により変更を起こすべき属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
