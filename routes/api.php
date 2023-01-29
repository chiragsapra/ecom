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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'App\Http\Controllers\OrderController@orders');
    Route::post('create', 'App\Http\Controllers\OrderController@create');
    Route::post('{id}/add', 'App\Http\Controllers\OrderController@add');
    Route::post('{id}/pay', 'App\Http\Controllers\OrderController@pay');
    Route::delete('{id}/delete', 'App\Http\Controllers\OrderController@delete');
});
