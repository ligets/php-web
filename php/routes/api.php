<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return asset('/storage/sneakers/sneakers-1.jpg');
});

Route::get('/items', 'App\Http\Controllers\ItemsController@index');
