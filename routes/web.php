<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'AdminIndex'])->name('admin.home')->middleware('is_admin');
