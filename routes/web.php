<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLogController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    } else {
        return view('auth.login');
    }
});
Auth::routes();




Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //user
    Route::post('user/filter/', [UserController::class, 'filter']);
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    
    //user group
    Route::post('userGroup/filter/', [UserGroupController::class, 'filter']);
    Route::get('userGroup', [UserGroupController::class, 'index'])->name('userGroup.index');

    //product
    Route::post('product/filter/', [ProductController::class, 'filter']);
    Route::get('product', [ProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::patch('product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    //product log
    Route::post('productLog/filter/', [ProductLogController::class, 'filter']);
    Route::get('productLog', [ProductLogController::class, 'index'])->name('product.index');
    

});
