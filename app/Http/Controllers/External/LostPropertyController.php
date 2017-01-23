<?php

/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Eloquents\LostItem;

class LostPropertyController extends Controller
{
    
    public function index(){

        // 現在の年度で，まだ落し物主に渡っていない，落し物一覧を取得する。
        $data = LostItem::
            select('lost_items.id AS id',
                    'lost_items.created_at AS created_at',
                    'lost_items.lost_item_name AS lost_item_name',
                    'places.room_name AS room_name',
                    'lost_items.note AS note',
                    'file_name AS file_name'
                    )
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->whereBetween('lost_items.created_at', 
                    [convertBeginningFiscalYear(null), convertEndFiscalYear(null)])
            ->get();

        return view('lostitem.property', ['data' => $data]);

    }
}
