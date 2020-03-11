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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function ($router) {
    Route::post('login', 'API\Auth\AuthController@login');
    Route::post('register', 'API\Auth\AuthController@register');
    // Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');
});


Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('customer-types', 'API\CustomerTypeController');
    Route::apiResource('provinces', 'API\ProvinceController');
    Route::apiResource('cities', 'API\CityController');
    Route::apiResource('subdistricts', 'API\SubdistrictController');
});
