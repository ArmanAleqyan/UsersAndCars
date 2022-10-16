<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [UserController::class, 'logout']);

    Route::post('CreateCar', [ProductController::class, 'CreateCar']);
    Route::get('DeleteCar/car_id={id}', [ProductController::class,'DeleteCar']);

    Route::get('AllUsers',[HomeController::class, 'AllUsers']);
    Route::get('SinglePageUser/user_id={id}', [HomeController::class,'SinglePageUser']);

    Route::get('AllCars',[HomeController::class, 'AllCars']);
});

