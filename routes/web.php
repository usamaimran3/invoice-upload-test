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

Route::prefix('invoices')->group(function () {
    Route::get('get','InvoiceController@create');
    Route::post('upload','InvoiceController@store')->name('upload');
});


Route::prefix('api')->group(function () {
    Route::get('get-invoices','InvoiceController@index');
    Route::post('upload-invoice','InvoiceController@save');
});