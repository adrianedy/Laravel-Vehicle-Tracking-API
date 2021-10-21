<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('login-user', 'Auth\LoginController@loginUser')->name('login-user');
Route::post('check-email', 'UtilityController@checkEmail')->name('check-email');
Route::post('check-phone', 'UtilityController@checkPhone')->name('check-phone');

Route::middleware('auth:api')->group(function () {
    Route::post('register-user', 'Auth\RegisterController@register')->name('register')->middleware('admin');
    Route::post('check-pin', 'Auth\PinController@check');
    Route::post('update-pin', 'Auth\PinController@update');

    
    Route::group(['as' => 'devices.', 'prefix' => 'devices'], function () {
        Route::get('/', 'DeviceController@index')->name('index');
        Route::post('/', 'DeviceController@store')->name('store');

        Route::group(['middleware' => 'device.owner', 'prefix'=> '{device}'], function () {
            Route::get('/', 'DeviceController@show')->name('show');
            Route::patch('/', 'DeviceController@update')->name('update');
            Route::delete('/', 'DeviceController@destroy')->name('destroy');

            Route::get('/history', 'DeviceController@history')->name('history');
            Route::patch('/location', 'DeviceController@location')->name('location')->withoutMiddleware(['throttle:60,1']);
        });
    });
    
    
    Route::patch('deactivate/{user}', 'MembershipController@deactivate')->name('deactivate')->middleware('admin');

    Route::group(['as' => 'members.', 'prefix' => 'members'], function () {
        Route::get('/', 'MembershipController@members')->name('index')->middleware('admin');
        Route::get('{user}', 'MembershipController@profile')->name('profile');
        Route::patch('{user}', 'MembershipController@update')->name('update');
    });
});
