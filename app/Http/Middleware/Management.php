<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

class Management
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/login');
            }
        }

        if(\Auth::check()){

            if(in_array(\Auth::user()->role, ['admin', 'manager'])){
                //ログインしているかつ、role=admin, manager の処理
                return $next($request);
            }else{
                return new Response(view('errors.error_msg', ['message' => 'あなたにはこのページへのアクセス権がありません。']));
            }
        }else{

            return redirect()->guest('/login');
        }

        return redirect()->guest('/login');
    }
}
