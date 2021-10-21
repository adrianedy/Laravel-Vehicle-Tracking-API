<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application admin page. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" and "auth:admin" middleware group, "/Admin" namespace suffix
| "admin" url prefix, and "admin." for the naming prefix.
| Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name('users.index');
Route::post('/users', 'UserController@store')->name('users.store');
Route::group(['as' => 'users.', 'prefix' => 'users/{user}'], function () {
    Route::get('/', 'UserController@edit')->name('edit');
    Route::patch('/', 'UserController@update')->name('update');
    Route::delete('/', 'UserController@destroy')->name('destroy');

    Route::group(['as' => 'devices.', 'prefix' => 'devices'], function () {
        Route::get('/', 'UserDeviceController@index')->name('index');
        Route::delete('/{device}', 'UserDeviceController@destroy')->name('destroy');
    });
});

Route::get('/devices', 'DeviceController@index')->name('devices.index');
Route::post('/devices', 'DeviceController@store')->name('devices.store');
Route::group(['as' => 'devices.', 'prefix' => 'devices/{device}'], function () {
    Route::get('/', 'DeviceController@edit')->name('edit');
    Route::patch('/', 'DeviceController@update')->name('update');
    Route::delete('/', 'DeviceController@destroy')->name('destroy');
    Route::get('/print', 'DeviceController@print')->name('print');
});
