<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::get('/websites', [Api\WebsiteController::class, 'index']);
Route::post('/subscribe', [Api\WebsiteController::class, 'subscribe']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/posts', [Api\PostController::class, 'index']);
    Route::post('/post/store', [Api\PostController::class, 'store']);
});
