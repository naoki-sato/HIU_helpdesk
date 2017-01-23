<?php

namespace App\Http\Controllers\Management\Item;

use App\Models\Management\Item\ExportItemModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Input;

class RegistrationItemExcelController extends Controller
{

    // エクスポート用アイテムモデル
    private $export_item_model;


    /**
     * RegistrationItemExcelController constructor.
     */
    public function __construct()
    {
        $this->export_item_model = new ExportItemModel();
    }

    /**
     * 貸出用アイテムをDBに登録する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postImport(Request $request)
    {

        // 正常にインポート処理がされたかどうか
        $is_import_success = null;

        // バリデーション
        $this->validate($request, ['file_input' => 'required']);

        // CSV形式で記載されている貸出用アイテムをDBにインポート
        $is_import_success = $this->export_item_model->importItems($request);

        if (!$is_import_success) {
            session()->flash('alert_message', '<h3>[item_cd]が重複しているか，形式が間違っているため，Importできませんでした。</h3>');
        } else {
            session()->flash('success_message', '<h3>正常にImportできました。</h3>');
        }

        return back();
    }


    /**
     * 貸出用アイテム一覧をCSV形式で出力する
     *
     * @param Request $request
     */
    public function postExport(Request $request)
    {
        // 貸出用アイテム一覧をCSV形式で出力する
        $this->export_item_model->exportItems();
    }

}
