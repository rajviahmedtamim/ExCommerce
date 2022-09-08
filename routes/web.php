<?php

use Illuminate\Support\Facades\Route;


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//frontend all routes here
Route::group(['namespace'=>'App\Http\Controllers\Front'], function (){
    Route::get('/','IndexController@index');
    Route::get('/product-details/{slug}','IndexController@productDetails')->name('product.details');
});

