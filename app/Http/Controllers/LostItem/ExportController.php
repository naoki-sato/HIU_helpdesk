<?php

/**
 * @version 2017/01/21
 * @author  naoki.s 1312007
 */

namespace App\Http\Controllers\LostItem;

use App\Models\LostItem\ExcelModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LostItem\LostItemApiController;

class ExportController extends Controller
{

    // エクセルモデル
    private $excel_model;


    /**
     * ExportController constructor.
     */
    public function __construct()
    {
        $this->excel_model = new ExcelModel();
    }

    /**
     * 通し番号をエクセル形式で出力
     *
     * @param Request $request
     */
    public function postSerial(Request $request)
    {
        /* 変数部 */
        $post  = null; // ポストされた全データ
        $start = null; // 通し番号の開始数字
        $end   = null; // 通し番号の終了数字

        // バリデーション
        $this->validate($request, [
                'start_number' => 'required|numeric',
                'end_number'   => 'required|numeric']);

        $post  = $request->all();
        $start = min($post['start_number'], $post['end_number']);
        $end   = max($post['start_number'], $post['end_number']);

        // 通し番号をエクセル形式で出力
        $this->excel_model->exportSerial($start, $end);
    }


    /**
     * 落し物履歴をエクセル形式で出力
     *
     * @param Request $request
     */
    public function postHistory(Request $request)
    {
        /* 変数部 */
        $post  = null; // ポストされた全データ
        $year  = null; // 年度

        // バリデーション
        $this->validate($request, ['year' => 'required|numeric']);

        $post = $request->all();
        $year = $post['year'];

        // 落し物履歴をエクセル形式で出力
        $this->excel_model->exportHistory($year);
    }
}
