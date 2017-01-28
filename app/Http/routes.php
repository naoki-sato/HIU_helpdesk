<?php
/**
 * @version 2017/01/23
 * @author  naoki.s 1312007
 */

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


Route::group(['middlewareGroups' => ['web']], function(){


    Route::resource('/', 'IndexController', ['only' => ['index']]);

    // 外部用の簡易版落し物リスト(guest時のみ)
    Route::group(['namespace' => 'LostItem'], function(){
        Route::resource('lost-item', 'LostitemController', ['only' => ['index']]);
    });
    
    // ログイン機能
    Route::auth();

    Route::group(['middleware' => ['auth']], function(){

        Route::resource('/', 'IndexController', ['only' => ['index', 'store']]);

        // 貸出アイテム関連
        Route::group(['namespace' => 'Lending'], function(){
            Route::controller('lend-item', 'StatusController');
            Route::resource('lend-item-export', 'ExportController', ['only' => ['store']]);
        });

        // 落し物関連
        Route::group(['namespace' => 'LostItem'], function(){
            Route::resource('lost-item', 'LostitemController', ['only' => ['store', 'show', 'update', 'destroy']]);
            Route::controller('lost-item-export', 'ExportController');
        });

        // スタッフ・User・品(カメラ・三脚など)・シフトスケジュールの登録や編集
        Route::group(['namespace' => 'Management'], function(){

            Route::group(['namespace' => 'Shift'], function(){
                // シフト表の表示
                Route::resource('shift-table', 'ShiftController', ['only' => ['index']]);
            });

            // User
            Route::group(['namespace' => 'User'], function(){
                // User登録API
                Route::resource('registration-user-api', 'RegistrationUserApiController', ['only' => ['show']]);

                // すべてのUser登録管理をするため，role:adminの限られた人のみ
                Route::group(['middleware' => ['admin']], function(){
                    Route::controller('registration-user', 'RegistrationUserController');
                });
            });

            // 権限ある管理者マネージャーのみ (一般スタッフは除く)
            Route::group(['middleware' => ['management']], function(){

                Route::group(['namespace' => 'Shift'], function(){
                    // シフト表の変更
                    Route::resource('shift-table', 'ShiftController', ['only' => ['store']]);
                });

                Route::group(['namespace' => 'Staff'], function(){
                    // スタッフの登録・編集・削除
                    Route::resource('registration-staff', 'RegistrationStaffController');
                    // スタッフの復帰
                    Route::resource('can-be-back-staff', 'BeBackStaffController', ['only' => ['index', 'update']]);
                });
                Route::group(['namespace' => 'Item'], function(){
                    // 貸出アイテムの登録・編集・削除
                    Route::resource('registration-item', 'RegistrationItemController', ['only' => ['index', 'store']]);
                    Route::controller('registration-item-excel', 'RegistrationItemExcelController');
                });
            });

        });

        // スタッフ各自がメアド・電話番号を変更
        Route::group(['namespace' => 'Auth', 'prefix' => 'setting'], function(){
            Route::controller('/', 'SettingController');
        });
    });
});
