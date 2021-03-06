<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\SubscriptionController;
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

Route::post('token', [RegisterController::class, 'getToken']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/rate', [RateController::class, 'rate'])->middleware('only_one_rate');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
});

Route::group(['prefix' => '/apartment'], function () {
    Route::get('/', [ApartmentController::class, 'index']);
    Route::post('/', [ApartmentController::class, 'store']);
    Route::put('/{id}', [ApartmentController::class, 'update']);
    Route::delete('/{id}', [ApartmentController::class, 'destroy']);
});
Route::group(['prefix' => '/category'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});
