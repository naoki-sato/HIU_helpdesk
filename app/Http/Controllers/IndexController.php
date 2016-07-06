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


        // dd($info_mes);

        // dd($info_mes);
        return view('welcome', ['info_mes' => $info_mes]);
    }


    public function store(Request $request){

        $post = $request->all();
        // dd($post['info_mes']);
        $request->session()->flash('info_message', $post['info_mes']);
        return view('welcome');
    }
}
