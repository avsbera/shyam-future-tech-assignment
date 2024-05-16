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

Route::get('/', [UserController::class, 'index'])->name('user.index');
Route::post('/add', [UserController::class, 'add'])->name('user.add');
Route::post('/edit', [UserController::class, 'edit'])->name('user.edit');
Route::post('/delete', [UserController::class, 'delete'])->name('user.delete');
Route::post('/view', [UserController::class, 'view'])->name('user.view');
Route::post('/sort', [UserController::class, 'sort'])->name('user.sort');
Route::get('/fetch', [UserController::class, 'fetchData'])->name('user.fetch');
