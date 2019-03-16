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
    Route::resource('users', 'UserController');
    Route::prefix('users')->group(function (){
        Route::post('columns', 'UserController@columns')->name('users.columns');
        Route::post('data', 'UserController@data')->name('users.data');
        Route::get('permissions/{user_id}', 'UserController@permissions')->name('users.permissions');
    });
    //User routes end here;

    //Group routes begin here:
    Route::resource('groups', 'GroupController');
    Route::prefix('groups')->group(function (){
       //
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