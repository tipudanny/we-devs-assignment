<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/', function () {
    return "Api Version is: v1.0.0 & Laravel version is: ".app()->version();
});

Route::group(['prefix' => '/v1'], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('refresh',  [AuthController::class, 'refresh']);

//  route for products
    Route::group(['middleware'=>'auth'], function () {

        Route::get('products',          [ProductController::class, 'index']);
        Route::post('products',         [ProductController::class, 'store']);
        Route::get('products/{id}',     [ProductController::class, 'show']);
        Route::post('products/{id}',    [ProductController::class, 'update']);
        Route::delete('products/{id}',  [ProductController::class, 'destroy']);

//      route for logout
        Route::post('logout',   [AuthController::class, 'logout']);

    });
});
