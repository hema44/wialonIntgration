<?php

use App\services\wialonSystemService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/login_back', [\App\Http\Controllers\HomeController::class,'login'])->name("login_back");

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/liveTracking', [App\Http\Controllers\WialonController::class, 'liveTracking'])->name('liveTracking');
Route::get('/liveTrackingJson', [App\Http\Controllers\WialonController::class, 'liveTrackingJson'])->name('liveTrackingJson');
