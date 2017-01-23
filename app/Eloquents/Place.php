<?php

/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * 場所から落し物を取得
     */
    public function lostitem()
    {
        return $this->hasMany('App\Models\LostItem');
    }
}
