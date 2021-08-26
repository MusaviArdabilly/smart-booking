<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListController;

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

Route::get('floor/list', [ListController::class, 'listFloor']);

// Route::get('floor/{floor}/sector/list', [ListController::class, 'listSector']);
// Route::get('floor/{floor}/sector/{sector}/desk/list', [ListController::class, 'listDesk']);

Route::get('sector/{floor}/list', [ListController::class, 'listSector']);
Route::get('desk/{sector}/list', [ListController::class, 'listDesk']);

// Route::middleware('auth:api')->group(function () {
//     // Route::resource('floor', FloorControllers::class);
// });
