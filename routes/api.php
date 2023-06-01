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

Route::post('/login','App\Http\Controllers\AuthController@login');
Route::post('/register','App\Http\Controllers\AuthController@register');
Route::post('/verify','App\Http\Controllers\AuthController@verify');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');

    Route::prefix('tags')->group(function () {
        Route::get('/', 'App\Http\Controllers\TagController@index');
        Route::post('store', 'App\Http\Controllers\TagController@store');
        Route::get('show/{id}', 'App\Http\Controllers\TagController@show');
        Route::post('update/{id}', 'App\Http\Controllers\TagController@update');
        Route::get('delete/{id}', 'App\Http\Controllers\TagController@destroy');
    });

    Route::prefix('posts')->group(function () {
        Route::get('/', 'App\Http\Controllers\PostController@index');
        Route::post('store', 'App\Http\Controllers\PostController@store');
        Route::get('show/{id}', 'App\Http\Controllers\PostController@show');
        Route::post('update/{id}', 'App\Http\Controllers\PostController@update');
        Route::get('delete/{id}', 'App\Http\Controllers\PostController@destroy');
    });
    Route::prefix('users')->group(function () {
        Route::get('posts/', 'App\Http\Controllers\UserController@getUsersPosts');
        Route::get('viewDeletedPosts', 'App\Http\Controllers\UserController@viewDeletedPosts');
        Route::post('restoreDeletedPosts/{id}', 'App\Http\Controllers\UserController@restoreDeletedPosts');
    });
    Route::prefix('stats')->group(function () {
        Route::get('numberOfUsers', 'App\Http\Controllers\StatsController@numberOfUsers');
        Route::get('numberOfPosts', 'App\Http\Controllers\StatsController@numberOfPosts');
        Route::get('usersHasNoPosts', 'App\Http\Controllers\StatsController@usersHasNoPosts');

    });

});
