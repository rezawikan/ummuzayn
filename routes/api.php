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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('customer')->middleware(['assign.guard:customers'])->group(function () {
    Route::post('forgot', 'API\Auth\CustomerAuthController@forgot'); //done
    Route::post('reset', 'API\Auth\CustomerAuthController@reset');  //done
    Route::post('login', 'API\Auth\CustomerAuthController@login'); //done

    // customer logged
    Route::middleware(['auth:customers'])->group(function () {
        Route::post('logout', 'API\Auth\CustomerAuthController@logout'); // done
        Route::put('update/{customer}', 'API\Auth\CustomerAuthController@update'); //
        Route::get('me', 'API\Auth\CustomerAuthController@me'); // done
    });
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'API\Auth\AuthController@login'); //done

    // admin logged
    Route::middleware(['auth:admins'])->group(function () {
        Route::post('logout', 'API\Auth\AuthController@logout'); //done
        Route::post('register', 'API\Auth\AuthController@register'); //done
        Route::put('update/{admin}', 'API\Auth\AuthController@update'); //done
        Route::get('me', 'API\Auth\AuthController@me'); //done

        // user
        Route::post('register-user', 'API\Auth\CustomerAuthController@register'); // done
        Route::put('update-user', 'API\Auth\CustomerAuthController@update');
        Route::get('me-user/{customer}', 'API\Auth\CustomerAuthController@me');
        Route::post('reset-password-user', 'API\Auth\CustomerAuthController@reset');
    });
});

Route::middleware(['auth:admins'])->group(function () {
    Route::apiResource('customer-types', 'API\CustomerTypeController');
    Route::apiResource('customer-address', 'API\CustomerAddressController');
    Route::apiResource('customers', 'API\CustomerController');
    Route::apiResource('provinces', 'API\ProvinceController');
    Route::apiResource('cities', 'API\CityController');
    Route::apiResource('subdistricts', 'API\SubdistrictController');
    Route::apiResource('products', 'API\ProductController');
    Route::apiResource('product/stocks', 'API\ProductStockController');
    Route::apiResource('product/images', 'API\ProductImageController');
    Route::apiResource('product/status', 'API\ProductStatusController');
    Route::apiResource('product/categories', 'API\ProductCategoryController');
    Route::apiResource('product-variations', 'API\ProductVariationController');
    Route::apiResource('product-variation-types', 'API\ProductVariationTypeController');
});
