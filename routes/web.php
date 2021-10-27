<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;

use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\DeskController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AssessmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/synapsis', [WelcomeController::class, 'synapsis']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

$router->group(['prefix' => 'admin', 'middleware' => 'is_admin'], function () use ($router) {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('user', UserController::class);

    Route::get('floor/list', [FloorController::class, 'list'])->name('floor.list');
    Route::resource('floor', FloorController::class);

    Route::get('floor/{floor}/sector/list', [SectorController::class, 'list'])->name('floor.sector.list');
    Route::resource('floor.sector', SectorController::class);

    Route::get('floor/{floor}/sector/{sector}/desk/list', [DeskController::class, 'list'])->name('floor.sector.desk.list');
    Route::resource('floor.sector.desk', DeskController::class);

    Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('booking/{booking}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');

    Route::get('assessment', [AssessmentController::class, 'index'])->name('assessment.index');
});
