<?php

namespace App\Http\Controllers\Lending;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Status;

class ExportController extends Controller
{

    public function postExport(){

        $data = Status::withTrashed()
            ->select(
                    'statuses.id AS ID',
                    'statuses.item_cd AS 機材コード',
                    'items.description AS 機材説明',
                    'statuses.lended_user_cd AS 学籍番号',
                    'users.user_name AS 氏名',
                    'statuses.comment AS 備考',
                    'admins.name AS 貸出担当者',
                    'A.name AS 返却担当者',
                    'statuses.created_at AS 貸出日時',
                    'statuses.deleted_at AS 返却日時'
                    )
            ->leftJoin('admins', 'admins.id', '=', 'statuses.lended_staff_id')
            ->leftJoin('admins as A', 'A.id', '=', 'statuses.returned_staff_id')
            ->leftJoin('items', 'items.item_cd', '=', 'statuses.item_cd')
            ->leftJoin('users', 'users.user_cd', '=', 'statuses.lended_user_cd')
            ->get();

        Excel::create('helpdesk_貸出履歴', function($excel) use($data) {
            $excel->sheet('貸出履歴一覧', function($sheet) use($data) {
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
