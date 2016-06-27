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



Route::group(['middleware' => ['auth']], function(){

    // 貸出アイテム関連
    Route::group(['namespace' => 'Lending'], function(){
        Route::controller('lend-item', 'StatusController');
        Route::resource('lend-item-api', 'StatusApiController');
        Route::controller('lend-item-export', 'ExportController');
    });

    // 落し物関連
    Route::group(['namespace' => 'LostItem'], function(){
        Route::resource('lost-item', 'LostItemController');
        Route::resource('lost-item-api', 'LostItemApiController');
        Route::controller('lost-item-export', 'ExportController');
    });

    // スタッフ・学生・品(カメラ・三脚など)の登録や編集
    Route::group(['namespace' => 'Management'], function(){
        // User登録API
        Route::resource('registration-user-api', 'RegistrationUserApiController');

        // すべてのUser登録管理をするため，role:adminの限られた人のみ
        Route::group(['middleware' => ['admin']], function(){
            Route::controller('registration-user', 'RegistrationUserController');
        });

        // 権限ある管理者マネージャーのみ (一般スタッフは除く)
        Route::group(['middleware' => ['management']], function(){
            // スタッフの登録・編集・削除
            Route::resource('registration-staff', 'RegistrationStaffController');
            Route::resource('registration-staff-api', 'RegistrationStaffApiController');

            // 貸出アイテムの登録・編集・削除
            Route::resource('registration-item', 'RegistrationItemController');
            Route::resource('registration-item-api', 'RegistrationItemApiController');
            Route::controller('registration-item-excel', 'RegistrationItemExcelController');
        });
    });

    // スタッフ各自がメアド・電話番号を変更
    Route::group(['namespace' => 'Auth', 'prefix' => 'setting'], function(){
        Route::controller('/', 'SettingController');
    });
});
