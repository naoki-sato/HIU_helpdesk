<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Message;
use Carbon\Carbon;

class IndexController extends Controller
{

    public function index(){

        $info_mes = Message::select('messages.description AS description',
                                    'messages.created_at AS created_at',
                                    'admins.name AS name')
                            ->leftJoin('admins', 'admins.id', '=', 'messages.staff_id')
                            ->where('messages.created_at', '>', Carbon::now()->subDay(30))->get();

        return view('welcome', ['info_mes' => $info_mes]);
    }


    public function store(Request $request){

        $post = $request->all();

        try{
            $message = new Message;
            $message->description   = $post['info_mes'];
            $message->staff_id      = $request->user()['id'];
            $message->save();
        } catch(\PDOException $e) {
            $request->session()->flash('alert_message', '投稿失敗しました。');
        }

        return redirect('/');
    }
}
