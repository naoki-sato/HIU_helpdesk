<?php

namespace App\Http\Controllers\LostItem;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LostItem\LostItemApiController;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\LostItem;

class ExportController extends Controller
{

    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
                'start_number' => 'sometimes|required|numeric',
                'end_number'   => 'sometimes|required|numeric',
                'year'         => 'sometimes|required|numeric'];
    }


    public function postSerial(Request $request)
    {

        $post = $request->all();
        $this->validate($request, $this->validation_rules);

        $start = min($post['start_number'], $post['end_number']);
        $end   = max($post['start_number'], $post['end_number']);

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
                for ($i=$start; $i<=$end; $i+=5) {
                        $sheet->appendRow([$i, $i+1, $i+2, $i+3, $i+4]);
                }

            });
         })->export('xls');
    }



    public function postHistory(Request $request)
    {

        $post = $request->all();
        $this->validate($request, $this->validation_rules);
        $year = $post['year'];

        /* Eloquentだと上手くExcelにexportできないため，queryをゴリゴリ書いた */
        $data = LostItem::withTrashed()
            ->leftJoin('users', 'users.id', '=', 'lost_items.reciept_staff_id')
            ->leftJoin('students', 'students.id', '=', 'lost_items.student_id')
            ->leftJoin('places', 'places.id', '=', 'lost_items.place_id')
            ->leftJoin('users as A', 'A.id', '=', 'lost_items.delivery_staff_id')
            ->select('lost_items.id AS 番号',
                    'lost_items.created_at AS 受取日',
                    'lost_items.lost_item_name AS アイテム名',
                    'places.room_name AS 場所',
                    'users.name AS 受取担当者',
                    'lost_items.note AS 備考',
                    'lost_items.deleted_at AS 引渡日',
                    'A.name AS 引渡担当者',
                    'students.student_name AS 落し物主'
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
