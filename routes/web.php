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
    return redirect(route('api-doc', 'v2'));
});

Route::group(['prefix' => 'api-doc/{version}', 'middleware' => ['docs'], 'as' => 'api-doc', 'where' => ['version' => 'v1|v2']], function () {
    Route::get('/', 'Web\DocController@mobigps');
});

Route::prefix('admin')->namespace('Admin\Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login')->middleware('guest:admin');
    Route::post('login', 'LoginController@login')->middleware('guest:admin');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});
