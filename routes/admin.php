<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/admin-login', [App\Http\Controllers\Auth\LoginController::class, 'AdminLogin'])->name('admin.login');
//Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'AdminIndex'])->name('admin.home')->middleware('is_admin');

Route::group(['namespace'=>'App\Http\Controllers\Admin', 'middleware'=>'is_admin'], function (){
    Route::get('/admin-home', 'AdminController@admin')->name('admin.home');
    Route::get('/admin-logout', 'AdminController@logout')->name('admin.logout');

    //    Category Routes
    Route::group(['prefix'=>'category'],function (){
        Route::get('/', 'CategoryController@index')->name('category.index');
        Route::post('/store', 'CategoryController@store')->name('category.store');
        Route::get('/delete/{id}', 'CategoryController@destroy')->name('category.delete');
        Route::get('/edit/{id}', 'CategoryController@edit');
        Route::post('/update', 'CategoryController@update')->name('category.update');
    });

    //    Category Routes
    Route::group(['prefix'=>'subcategory'],function (){
        Route::get('/', 'SubcategoryController@index')->name('subcategory.index');
        Route::post('/store', 'SubcategoryController@store')->name('subcategory.store');
        Route::get('/delete/{id}', 'SubcategoryController@destroy')->name('subcategory.delete');
        Route::get('/edit/{id}', 'SubcategoryController@edit');
         Route::post('/update', 'SubcategoryController@update')->name('subcategory.update');
    });

    //Child-category routes
    Route::group(['prefix'=>'childcategory'],function (){
        Route::get('/', 'ChildcategoryController@index')->name('childcategory.index');
        Route::post('/store', 'ChildcategoryController@store')->name('childcategory.store');
        Route::get('/delete/{id}', 'ChildcategoryController@destroy')->name('childcategory.delete');
        Route::get('/edit/{id}', 'ChildcategoryController@edit');
        Route::post('/update', 'ChildcategoryController@update')->name('childcategory.update');
    });
});