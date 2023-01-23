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




Route::group(['middleware' => 'auth'], function () {
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

    //product
    Route::post('product/filter/', 'ProductController@filter');
    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product', 'ProductController@store')->name('product.store');
    Route::get('product/{id}/edit', 'ProductController@edit')->name('product.edit');
    Route::patch('product/{id}', 'ProductController@update')->name('product.update');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');
    //product log
    Route::post('productLog/filter/', 'ProductLogController@filter');
    Route::get('productLog', 'ProductLogController@index')->name('product.index');
    

});
