<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * 生徒から落し物を取得
     */
    public function lostitem()
    {
        return $this->hasMany('App\Models\LostItem');
    }
}
