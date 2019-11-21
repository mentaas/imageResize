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

Route::get('resizeImages', 'ResizeImageController@index');
Route::get('resizeImages/{id}', 'ResizeImageController@show');
Route::post('resizeImages', 'ResizeImageController@store');
//Route::put('resizeImages/{article}', 'ResizeImageController@update');
Route::delete('resizeImages/{id}', 'ResizeImageController@delete');
Route::delete('resizeImages/delete/{url}', 'ResizeImageController@deleteImages')->where(['url' => '(.*)']);
Route::delete('resizeImages/{url}/{width}/{height}', 'ResizeImageController@deleteUrl')->where(['url' => '(.*)']);
Route::get('resizeImages/image/{url}', 'ResizeImageController@showImage');

