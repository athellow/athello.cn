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

Route::middleware('throttle:60,1')->prefix('v1')->group(function($router) {
    Route::get('/posts', 'Api\PostController@index');
    Route::get('/post/{id}', 'Api\PostController@detail')->where(['id' => '[1-9]{1}[0-9]*']);

    Route::get('/ilogs', 'Api\IlogController@index');
    Route::post('/ilogs', 'Api\IlogController@store');
    Route::get('/ilog/{id}', 'Api\IlogController@detail')->where(['id' => '[1-9]{1}[0-9]*']);

    Route::get('/user', 'Api\UserController@index');
    Route::post('/user', 'Api\UserController@store');

    Route::post('/upload', 'Api\UploadController@do')->name('upload.do');
});
