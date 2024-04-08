<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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
Route::controller(AuthController::class)->group(function(){
    Route::get('/', 'authenticate');
    Route::get('/authenticate', 'authenticate')->middleware('verifyshopify')->name('authenticate');
});

Route::controller(HomeController::class)->group(function(){
    Route::get('/home', 'index')->name('home');
});

Route::controller(WebhookController::class)->prefix('webhook')->group(function(){
   Route::any('order/create','orderCreate');
});