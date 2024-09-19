<?php

use App\Models\Positions;
use Illuminate\Support\Facades\Route;
//use namespace App\Http\Middleware\Verify;


Route::get('/', function () { return view('welcome');});

Route::get('/users', [\App\Http\Controllers\UserController::class, 'getAll'])->name('users-list');
Route::get('/users/create', function () { return view('user_form', ['positions' => (new Positions)->get()]);})->name('users-create-form');
Route::post('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('user-create');
Route::get('/users/import', [App\Http\Controllers\UserController::class, 'import'])->name('import-users');
Route::get('/users/loadMore', [App\Http\Controllers\UserController::class, 'loadMore'])->name('load-users');

Route::get('/api', function () { return view('documentation');})->name('api');

