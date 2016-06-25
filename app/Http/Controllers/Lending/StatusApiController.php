<?php

namespace App\Http\Controllers\Lending;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Item;
use App\Http\Controllers\Management\RegistrationUserApiController;

class StatusApiController extends Controller
{
    
    public $validation_rules;
    private $registration_user_api;

    public function __construct()
    {
        $this->validation_rules = [
                'user_name'   => 'sometimes|required',
                'user_cd'     => 'sometimes|required',
                'phone_no'    => 'sometimes|required|numeric'];

        $this->registration_user_api = new RegistrationUserApiController();
    }


    /**
     * Display a listing of the resource.
     *
     * @return Json
     */
    public function index()
    {
        $data = Status::select(
                    'statuses.id AS id',
                    'statuses.item_cd AS item_cd',
                    'items.description AS description',
                    'users.user_cd AS user_cd',
                    'users.user_name AS user_name',
                    'users.phone_no AS phone_no',
                    'admins.name AS lended_staff_name',
                    'statuses.created_at AS created_at',
                    'statuses.comment AS comment'
                    )
                ->leftJoin('admins', 'admins.id', '=', 'statuses.lended_staff_id')
                ->leftJoin('items', 'items.item_cd', '=', 'statuses.item_cd')
                ->leftJoin('users', 'users.user_cd', '=', 'statuses.lended_user_cd')
                ->whereNull('statuses.deleted_at')
                ->get();

        return response()->json($data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return success : true, fail : false
    */
    public function store(Request $request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $post = $request->all();
        $user_name  = $post['user_name'];
        $user_cd    = mb_convert_kana($post['user_cd'], 'sa');
        $phone_no   = $post['phone_no'];
        $lend_items = $post['lend_item'];
        $lended_staff_id = $request->user()['id'];
        $comment    = $post['note'];
        $is_already_user = User::where('user_cd', '=', $user_cd)->first();

        if(!$is_already_user){
            $this->registration_user_api->store($request);
        }else{
            $this->registration_user_api->update($request);
        }

        $checked_list = self::checkLendedItems($lend_items);

        if(!$checked_list){
            return false;
        }

        foreach ($checked_list as $key => $value) {
            try{
                $status = new Status;

                $status->lended_staff_id   = $lended_staff_id;
                $status->lended_user_cd    = $user_cd;
                $status->item_cd           = $value;
                $status->comment           = $comment;
                $status->save();
            } catch(\PDOException $e) {
                return false;
            }
        }
        return true;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return success : true, fail : false
     */
    public function destroy($request)
    {

        // バリデーションに引っかかったら, false
        $validation = Validator::make($request->all(), $this->validation_rules);
        if($validation->fails()) return false;

        $return_items = $request->get('return_item');
        $checked_list = self::checkReturnedItems($return_items);


        if(!$checked_list){
            return false;
        }

        // item_cdと引渡担当者noを更新してソフト削除
        foreach ($checked_list as $key => $value) {
            try{

                Status::where('item_cd', '=', $value)
                    ->update(['returned_staff_id' => $request->user()['id']]);

                Status::where('item_cd', '=', $value)->delete();
            } catch(\Exception $e) {
                return false;
            }
        }

        return true;
    }

    /*
     * 貸出中のアイテムが混じっていないか、
     * DBに貸出アイテムとして登録されているか
     * の２点をチェック
     * 1つでも混じっていたらfalse
     */
    private function checkLendedItems($items)
    {

        $items = array_filter($items);

        foreach ($items as $key => $value) {
            // 貸出中のアイテムが混じっていないか
            $is_check = Status::where('item_cd', '=', $value)->first();
            if($is_check) return false;

            // DBに貸出アイテムとして登録されているか
            $is_check = Item::where('item_cd', '=', $value)->first();
            if(empty($is_check)) return false;
        }
        return $items;

    }

    /*
     * 貸出していないアイテムが混じっていないか、
     * DBに貸出アイテムとして登録されているか
     * の２点をチェック
     * 1つでも混じっていたらfalse
     */
    private function checkReturnedItems($items)
    {

        $items = array_filter($items);

        foreach ($items as $key => $value) {
            // 貸出中のアイテムが混じっていないか
            $is_check = Status::where('item_cd', '=', $value)->first();
            if(!$is_check) return false;

            // DBに貸出アイテムとして登録されているか
            $is_check = Item::where('item_cd', '=', $value)->first();
            if(empty($is_check)) return false;
        }
        return $items;

    }
}
