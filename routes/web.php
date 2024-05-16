<?php

use App\Http\Controllers\UserController;

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

Route::get('/', [UserController::class, 'index']);
Route::post('/add', [UserController::class, 'add']);
Route::post('/edit', [UserController::class, 'edit']);
Route::post('/delete', [UserController::class, 'delete']);
Route::post('/view', [UserController::class, 'view']);
Route::post('/sort', [UserController::class, 'sort']);
Route::get('/fetch', [UserController::class, 'fetchData']);
