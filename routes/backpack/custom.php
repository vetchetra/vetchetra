<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('category', 'CategoryCrudController');
    Route::crud('sale', 'SaleCrudController');
    Route::crud('saledetail', 'SaledetailCrudController');
    Route::crud('customer', 'CustomerCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('skincare', 'SkincareCrudController');
    Route::crud('slideshow', 'SlideshowCrudController');
    Route::crud('commune', 'CommuneCrudController');
    Route::crud('village', 'VillageCrudController');
    Route::crud('cart', 'CartCrudController');
    Route::crud('province', 'ProvinceCrudController');
    Route::crud('district', 'DistrictCrudController');


    Route::get('search-category', 'CategoryCrudController@api');
    Route::get('search-customer', 'CustomerCrudController@appp');
    Route::get('search-product', 'ProductCrudController@aaaa');
    Route::get('productttts/{id}', 'ProductCrudController@showw');
    Route::get('cusshoww', 'CustomerCrudController@searchh');
    Route::get('/revenue', 'CustomerCrudController@form01');


    Route::get('/provincesname', 'ProvinceCrudController@selectprovinceee');
    Route::get('/seldistrcitfromcommune', 'CommuneCrudController@selectajax');
    Route::get('/selprovincefromdistrict', 'DistrictCrudController@selectajax');
    Route::get('/selectnamedistrick', 'ProvinceCrudController@selectajax');

    Route::get('/selectprovince', 'ProvinceCrudController@selectprovinceee');
    Route::get('/selectcommune', 'ProvinceCrudController@selectcommune');
    Route::get('/selectdistrict', 'ProvinceCrudController@selectdistrict');








}); // this should be the absolute last line of this file
