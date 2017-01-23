<?php

namespace App\Models\Management\Item;

use App\Eloquents\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Input;

class ExportItemModel extends Model
{

    /**
     * CSV形式の貸出用アイテムをDBにインサートする
     *
     * @param Request $request
     * @return bool
     */
    public function importItems(Request $request)
    {

        // マルチインサートする値
        $merge_value = null;

        if (Input::hasFile('file_input')) {

            $path = Input::file('file_input')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();

            if (!empty($data) && $data->count()) {

                // マルチインサートするために，まとめる
                foreach ($data as $value) {
                    $merge_value[] =
                        [
                            'item_cd' => $value->item_cd,
                            'serial_cd' => $value->serial_cd,
                            'description' => $value->description,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                }

                // TODO:: refactoring
                try {
                    // 全消し
                    Item::truncate();

                    // マルチインサート
                    Item::insert($merge_value);

                }catch (\Exception $e){
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * 貸出アイテムをCSV形式でエクスポート
     */
    public function exportItems()
    {

        $items = Item::select('items.item_cd', 'items.serial_cd', 'items.description')->get();

        Excel::create('helpdesk_item_export', function($excel) use($items) {
            $excel->sheet('1', function($sheet) use($items){
                $sheet->fromArray($items);
            });
        })->export('csv');

    }
}
