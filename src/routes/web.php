<?php

Route::group(['namespace' => 'MateusJunges\ACL\Http\Controllers'], function (){
    //User routes begin here:
    Route::resource('users', 'UserController');
    Route::prefix('users')->group(function (){

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
});