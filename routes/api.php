<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authcontroller;
use App\Http\Controllers\Api\ProdukController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix'  => 'v1/auth'], function() {
    Route::post('register',[AuthController::class, 'register']);
    Route::post('login', [AuthController::class,'login']);

    Route::group(['middleware' => 'checktoken'], function() {
        Route::post('logout', [AuthController::class,'logout']);

    });    
});

Route::group(['prefix' => 'v1'], function(){
    Route::group(['middleware' => 'checktoken'], function() {
        Route::get('produk', [ProdukController::class, 'getproduk']);
        Route::post('produk', [ProdukController::class, 'createproduk']);
        Route::put('produk/{id}', [ProdukController::class, 'editproduk']);
        Route::delete('produk/{id}', [ProdukController::class, 'deleteproduk']);
    });
});
