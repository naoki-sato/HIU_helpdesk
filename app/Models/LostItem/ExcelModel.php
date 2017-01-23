<?php

/**
 * @version 2017/01/22
 * @author  naoki.s 1312007
 */

namespace App\Models\LostItem;

use App\Eloquents\LostItem;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class ExcelModel extends Model
{


    /**
     * 通し番号をエクセル形式で出力
     *
     * @param $start
     * @param $end
     */
    public function exportSerial($start, $end)
    {
        Excel::create('落し物通し番号', function($excel) use($start, $end) {
            $excel->sheet('1', function($sheet) use($start, $end){
                // Set font
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Arial Black',
                        'size'      =>  '36',
                        'bold'      =>  'true',
                        'underline' =>  'single',
                    )
                ));
                $sheet->setAllBorders('thin');
                $sheet->setWidth([
                    'A' => 4.65,
                    'B' => 4.65,
                    'C' => 4.65,
                    'D' => 4.65,
                    'E' => 4.65
                ]);

                // insert
                for ($i = $start; $i <= $end; $i += 5) {
                    $sheet->appendRow([$i, $i+1, $i+2, $i+3, $i+4]);
                }

            });
        })->export('xls');
    }


    /**
     * 落し物履歴をエクセル形式で出力
     *
     * @param $year
     */
    public function exportHistory($year)
    {

        $data = LostItem::withTrashed()
            ->leftJoin('admins', 'admins.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('users', 'users.user_cd', '=', 'lost_items.user_cd')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('admins as A', 'A.id', '=', 'lost_items.delivery_staff_id')
            ->select(
                'lost_items.id AS 番号',
                'lost_items.created_at AS 受取日',
                'lost_items.lost_item_name AS アイテム名',
                'places.room_name AS 場所',
                'admins.name AS 受取担当者',
                'lost_items.note AS 備考',
                'lost_items.deleted_at AS 引渡日',
                'A.name AS 引渡担当者',
                'users.user_cd AS 落し物主番号',
                'users.user_name AS 落し物主氏名'
            )
            ->whereBetween('lost_items.created_at',
                [convertBeginningFiscalYear($year), convertEndFiscalYear($year)])
            ->get();

        Excel::create('helpdesk_落し物_' . $year . '年度', function($excel) use($data) {
            $excel->sheet('落し物一覧', function($sheet) use($data) {
                // Set font
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'MS Pゴシック',
                        'size'      =>  '14'
                    )
                ));

                // insert
                $sheet->fromArray($data);
            });
        })->export('xls');

    }

}
