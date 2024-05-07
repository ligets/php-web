<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me')->middleware('jwt.auth');

});

Route::get('/items', 'App\Http\Controllers\ItemsController@index');

Route::group([
    'middleware' => 'jwt.auth'
], function ($router) {
    Route::apiResource('favorites', 'App\Http\Controllers\FavoritesController')->middleware('jwt.auth');
    Route::post('/orders', 'App\Http\Controllers\OrdersController@index');
});


