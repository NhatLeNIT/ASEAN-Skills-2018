<?php

use Illuminate\Http\Request;

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


Route::prefix('v1')->group(function () {
    Route::post('auth/login', 'Auth\LoginController@login');

    Route::middleware('auth.token')->group(function () {
        Route::get('auth/logout', 'Auth\LoginController@logout');
        Route::resource('place', 'PlaceController', ['only' => ['index', 'show']]);
        Route::get('route/search/{from}/{to}/{depart?}', 'RouteController@search');
        Route::post('route/selection', 'RouteController@selection');

        Route::middleware('auth.if.admin')->group(function () {
            Route::resource('place', 'PlaceController', ['only' => ['store', 'update', 'destroy']]);
            Route::resource('schedule', 'ScheduleController', ['only' => ['index', 'store', 'update', 'destroy']]);
        });
    });
});