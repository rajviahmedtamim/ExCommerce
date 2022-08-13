<?php

use Illuminate\Support\Facades\Route;


Route::get('/admin-login', [App\Http\Controllers\Auth\LoginController::class, 'AdminLogin'])->name('admin.login');
//Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'AdminIndex'])->name('admin.home')->middleware('is_admin');

Route::group(['namespace'=>'App\Http\Controllers\Admin', 'middleware'=>'is_admin'], function (){
    Route::get('/admin-home', 'AdminController@admin')->name('admin.home');
    Route::get('/admin-logout', 'AdminController@logout')->name('admin.logout');
    Route::get('/admin/password/change', 'AdminController@PasswordChange')->name('admin.password.change');
    Route::post('/admin/password/update', 'AdminController@PasswordUpdate')->name('admin.password.update');

    //    Category Routes
    Route::group(['prefix'=>'category'],function (){
        Route::get('/', 'CategoryController@index')->name('category.index');
        Route::post('/store', 'CategoryController@store')->name('category.store');
        Route::get('/delete/{id}', 'CategoryController@destroy')->name('category.delete');
        Route::get('/edit/{id}', 'CategoryController@edit');
        Route::post('/update', 'CategoryController@update')->name('category.update');
    });

    //global routes
    Route::get('/get-child-category/{id}', 'CategoryController@GetChildCategory');

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

    //Brand routes
    Route::group(['prefix'=>'brand'],function (){
        Route::get('/', 'BrandController@index')->name('brand.index');
        Route::post('/store', 'BrandController@store')->name('brand.store');
        Route::get('/delete/{id}', 'BrandController@destroy')->name('brand.delete');
        Route::get('/edit/{id}', 'BrandController@edit');
        Route::post('/update', 'BrandController@update')->name('brand.update');
    });

    //product routes
    Route::group(['prefix'=>'product'], function(){
        Route::get('/','ProductController@index')->name('product.index');
        Route::get('/create','ProductController@create')->name('product.create');
        Route::post('/store','ProductController@store')->name('product.store');
         Route::get('/delete/{id}','ProductController@destroy')->name('product.delete');
//         Route::get('/edit/{id}','BrandController@edit');
        // Route::post('/update','BrandController@update')->name('brand.update');
        Route::get('/active-featured/{id}','ProductController@activefeatured');
        Route::get('/not-featured/{id}','ProductController@notfeatured');
    });


    //Coupon routes
    Route::group(['prefix'=>'coupon'],function (){
        Route::get('/', 'CouponController@index')->name('coupon.index');
        Route::post('/store', 'CouponController@store')->name('store.coupon');
        Route::delete('/delete/{id}', 'CouponController@destroy')->name('coupon.delete');
        Route::get('/edit/{id}', 'CouponController@edit');
        Route::post('/update', 'CouponController@update')->name('update.coupon');
    });


    //Warehouse routes
    Route::group(['prefix'=>'warehouse'],function (){
        Route::get('/', 'WarehouseController@index')->name('warehouse.index');
        Route::post('/store', 'WarehouseController@store')->name('warehouse.store');
        Route::get('/delete/{id}', 'WarehouseController@destroy')->name('warehouse.delete');
        Route::get('/edit/{id}', 'WarehouseController@edit');
        Route::post('/update', 'WarehouseController@update')->name('warehouse.update');
    });

    //Setting routes
    Route::group(['prefix'=>'setting'],function (){
        //SEO setting
        Route::group(['prefix'=>'seo'],function (){
            Route::get('/', 'SettingController@seo')->name('seo.setting');
            Route::post('/update/{id}', 'SettingController@seoUpdate')->name('seo.setting.update');
        });
        //smtp setting
        Route::group(['prefix'=>'smtp'],function (){
            Route::get('/', 'SettingController@smtp')->name('smtp.setting');
            Route::post('/update/{id}', 'SettingController@smtpUpdate')->name('smtp.setting.update');
        });
        //website setting
        Route::group(['prefix'=>'website'],function (){
            Route::get('/', 'SettingController@website')->name('website.setting');
            Route::post('/update/{id}', 'SettingController@websiteUpdate')->name('website.setting.update');
        });
        //Page setting
        Route::group(['prefix'=>'page'],function (){
            Route::get('/', 'PageController@index')->name('page.index');
            Route::get('/create', 'PageController@create')->name('create.page');
            Route::post('/store', 'PageController@store')->name('page.store');
            Route::get('/delete/{id}', 'PageController@destroy')->name('page.delete');
            Route::get('/edit/{id}', 'PageController@edit')->name('page.edit');
            Route::post('/update/{id}', 'PageController@update')->name('page.update');
        });

        //Pickup Point Routs
        Route::group(['prefix'=>'pickup-point'],function (){
            Route::get('/', 'PickupController@index')->name('pickuppoint.index');
            Route::post('/store', 'PickupController@store')->name('pickuppoint.store');
            Route::delete('/delete/{id}', 'PickupController@destroy')->name('pickuppoint.delete');
            Route::get('/edit/{id}', 'PickupController@edit');
            Route::post('/update','PickupController@update')->name('pickuppoint.updated');
        });


    });
});
