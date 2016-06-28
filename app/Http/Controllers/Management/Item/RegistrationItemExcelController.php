<?php

namespace App\Http\Controllers\Management\Item;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Item;
use Input;

class RegistrationItemExcelController extends Controller
{
    
    public function postImport(Request $request)
    {

        $this->validate($request, ['file_input' => 'required']);

        if(Input::hasFile('file_input')){
            $path = Input::file('file_input')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();

            if(!empty($data) && $data->count()){

                Item::truncate();

                foreach ($data as $key => $value) {
                    try{
                        $item = new Item;
                        $item->item_cd      = $value->item_cd;
                        $item->serial_cd    = $value->serial_cd;
                        $item->description  = $value->description;
                        $item->save();
                    } catch(\PDOException $e) {

                        session()->flash('alert_message', '<h3>[item_cd]が重複しているか，形式が間違っているため，Importできませんでした。</h3>');
                        return back();
                    }
                }
            }
        }
        session()->flash('success_message', '<h3>正常にImportできました。</h3>');
        return back();

    }


    public function postExport(Request $request)
    {

        $items = Item::select('items.item_cd', 'items.serial_cd', 'items.description')->get();

        Excel::create('helpdesk_item_export', function($excel) use($items) {
            $excel->sheet('1', function($sheet) use($items){
                $sheet->fromArray($items);
            });
         })->export('csv');
    }

}
