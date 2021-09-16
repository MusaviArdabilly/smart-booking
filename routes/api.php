<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\Api\DeskController;

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

// Route::group(['middleware' => 'auth:api'], function () {
Route::get('floor/list', [FloorController::class, 'index']);
// });
Route::get('sector/{floor}/list', [SectorController::class, 'index']);
Route::get('desk/{sector}/list', [DeskController::class, 'index']);
Route::get('desk/{sector}/list/available/', [DeskController::class, 'available']);

Route::post('booking', [BookingController::class, 'store']);
Route::get('booking/{booking}', [BookingController::class, 'show']);
Route::get('booking/{user}/list', [BookingController::class, 'index']);
Route::get('booking/{user}/list/today', [BookingController::class, 'today']);
Route::patch('booking/{booking}/checkin', [BookingController::class, 'checkin']);
Route::patch('booking/{booking}/checkout', [BookingController::class, 'checkout']);

// Route::middleware('auth:api')->group(function () {
    // Route::resource('floor', FloorControllers::class);
// });
