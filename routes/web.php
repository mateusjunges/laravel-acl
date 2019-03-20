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
    Route::get('/groups/create', function (){
        $permissions = \MateusJunges\ACL\Http\Models\Permission::all();
        return view('acl::groups.create', compact('permissions'));
    });
    Route::post('/groups', function (){

    })->name('groups.store');
    Route::get('/groups/{id}', function ($id){
       $group = \MateusJunges\ACL\Http\Models\Group::find($id);
       $permissions = \MateusJunges\ACL\Http\Models\Permission::all();
       return view('acl::groups.edit', compact(['group', 'permissions']));
    });
    Route::put('groups/{id}', function ($id){

    })->name('groups.update');
});