<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['namespace' => 'MateusJunges\ACL\Http\Controllers', 'middleware' => ['web', 'auth']], function (){
    //User routes begin here:
    Route::resource('users', 'UserController', [
        'except' => [
            'create', 'update', 'show'
        ],
    ]);
    Route::prefix('users')->group(function (){
        Route::post('columns', 'UserController@columns')->name('users.columns');
        Route::post('data', 'UserController@data')->name('users.data');
        Route::get('permissions/{user_id}', 'UserController@permissions')->name('users.permissions');
        Route::get('trashed', 'UserController@trashed')->name('users.trashed');
        Route::prefix('permissions')->group(function (){
            Route::get('{user}', 'UserController@permissions')->name('users.permissions');
            Route::delete('{permission}', 'UserController@removePermission')->name('users.remove-permission');
        });
    });
    //User routes end here;

    //Group routes begin here:
    Route::resource('groups', 'GroupController', [
        'except' => [
            'show',
        ]
    ]);
    Route::prefix('groups' )->group(function (){
       Route::get('trashed', 'GroupController@trashed')->name('groups.trashed');
       Route::put('restore/{group}', 'GroupController@restore')->name('groups.restore');
       Route::prefix('permissions')->group(function (){
           Route::get('{group}', 'GroupController@permissions')->name('groups.permissions');
           Route::delete('{permission}', 'GroupController@removeGroupPermission')->name('groups.remove-permission');
       });
    });
    //Group routes end here;

    //Permission routes begin here
    Route::resource('permissions', 'PermissionController');
    Route::prefix('permissions')->group(function (){
        //
    });
    //Permission routes end here

    //Role routes begin here:
    Route::resource('roles', 'RoleController');
    Route::prefix('roles')->group(function (){
       //
    });
    //Role routes end here

    //Denied permissions routes
    Route::resource('denied-permissions', 'DeniedPermissionsController');
    Route::prefix('denied-permissions')->group(function (){
        //
    });
});