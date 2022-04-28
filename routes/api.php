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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'AuthController@login');

Route::prefix('v1')->middleware(['jwt.auth'])->group(function () {
    //AUTH
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');

    //API
    Route::apiResource('brand', 'BrandController');
    Route::apiResource('car', 'CarController');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('modelo', 'ModeloController');
    Route::apiResource('rent', 'RentController');
});

//REFRESH TOKEN
Route::post('refresh', 'AuthController@refresh');
