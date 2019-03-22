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
    Route::group(['namespace' => 'Groups'], function (){
        Route::resource('groups', 'GroupController', [
            'except' => [

            ]
        ]);
        Route::prefix('groups')->group(function (){
           Route::delete('permissions/{group}/{permission}', 'GroupController@removePermission')->name('groups.remove-permission');
        });
    });
    Route::group(['namespace' => 'Users', 'prefix' => 'users'], function (){
       Route::post('permissions', 'UserPermissionController@store')->name('user.permissions.store');
       Route::get('permissions/create', 'UserPermissionController@create')->name('user.permissions.create');
       Route::get('{user}/permissions', 'UserPermissionController@show')->name('user.permissions.show');
       Route::delete('permissions/{user}/{permission}', 'UserPermissionController@destroy')->name('user.permissions.remove');

    });
});