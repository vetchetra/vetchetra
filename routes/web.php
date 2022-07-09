<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    //return view('welcome');
    return view('frontend.index');
});
Route::get('viewskincaree/{id}',[\App\Http\Controllers\Admin\SkincareCrudController::class,'vvviewskincare']);
Route::get('/showcart',[\App\Http\Controllers\Admin\CartCrudController::class, 'showcartt']);
Route::get('/addcart/{id}',[\App\Http\Controllers\Admin\CartCrudController::class, 'addcart']);
Route::get('/minusqty/{id}',[\App\Http\Controllers\Admin\CartCrudController::class, 'minusqty']);
Route::get('/plusqty/{id}',[\App\Http\Controllers\Admin\CartCrudController::class, 'plusqty']);
Route::get('/remove/{id}',[\App\Http\Controllers\Admin\CartCrudController::class, 'removecart']);
Route::get('/selectbrand/{brand}',[\App\Http\Controllers\Admin\SkincareCrudController::class,'selectbrand']);

//Route::get('/removecart/{id}',[\App\Http\Controllers\Admin\CartCrudController::class, 'removecart']);


