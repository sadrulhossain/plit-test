<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    } else {
        return view('auth.login');
    }
});
Auth::routes();


Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
//user
Route::post('user/filter/', 'UserController@filter');
Route::get('user', 'UserController@index')->name('user.index');
Route::get('user/create', 'UserController@create')->name('user.create');
Route::post('user', 'UserController@store')->name('user.store');
Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
Route::patch('user/{id}', 'UserController@update')->name('user.update');
Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

//user group
Route::post('userGroup/filter/', 'UserGroupController@filter');
Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');
Route::get('userGroup/create', 'UserGroupController@create')->name('userGroup.create');
Route::post('userGroup', 'UserGroupController@store')->name('userGroup.store');
Route::get('userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
Route::patch('userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');
Route::delete('userGroup/{id}', 'UserGroupController@destroy')->name('userGroup.destroy');


Route::group(['middleware' => 'auth'], function () {

});
