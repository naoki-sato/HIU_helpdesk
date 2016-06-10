<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');



Route::group(['middleware' => ['auth']], function(){

    // 落し物関連
    Route::group(['namespace' => 'LostItem'], function(){
        Route::controller('lost-item', 'LostItemController');
        Route::controller('lost-item-export', 'ExportController');
    });



    // スタッフ・学生・品(カメラ・三脚など)の登録
    Route::group(['namespace' => 'Management'], function(){


        Route::resource('registration-student', 'RegistrationStudentController');

        // 権限あるマネージャーのみ
        Route::group(['middleware' => ['management']], function(){
            // スタッフの登録・編集
            Route::resource('registration-staff', 'RegistrationStaffController');

        });

    });





});
