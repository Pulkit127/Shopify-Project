<?php

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
// Route::get('/',[App\Http\Controllers\AuthController::class,'index']);
Route::get('/', [App\Http\Controllers\AuthController::class, 'authenticate'])->middleware('verifyshopify')->name('authenticate');
Route::get('/authenticate/token', [App\Http\Controllers\AuthController::class, 'token'])->name('authenticate.token');
Route::post('/webhooks/orders-create', [App\Http\Controllers\HomeController::class,'handleOrderWebhook']);