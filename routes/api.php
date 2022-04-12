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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login','ApiController@login');
Route::post('/logout','ApiController@logout')->middleware(['auth:sanctum']);
Route::resource('blogs','ApiBlogController')->middleware(['auth:sanctum']);
//Route::put('/blogs/{id}','ApiBlogController@update')->middleware(['auth:sanctum']);
