<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\BookingController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('floor/list', [ListController::class, 'floor']);
Route::get('sector/{floor}/list', [ListController::class, 'sector']);
Route::get('desk/{sector}/list', [ListController::class, 'desk']);
Route::get('desk/{sector}/list/available/', [ListController::class, 'deskAvailable']);

Route::post('booking', [BookingController::class, 'store']);
Route::get('booking/{booking}', [BookingController::class, 'show']);
Route::get('booking/{user}/list', [ListController::class, 'booking']);
Route::get('booking/{user}/list/today', [ListController::class, 'bookingToday']);

// Route::middleware('auth:api')->group(function () {
    // Route::resource('floor', FloorControllers::class);
// });
