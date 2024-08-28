<?php

use App\Models\Users;
use Illuminate\Support\Facades\Route;


Route::get('/', function () { return view('welcome');});

// api routes
 Route::get('/users', [\App\Http\Controllers\ApiController::class, 'getUsers'])->name('users');
 Route::get('/users/{id}', [\App\Http\Controllers\ApiController::class, 'getOneUser'])->name('user');
 Route::get('/positions', [\App\Http\Controllers\ApiController::class, 'getPositions'])->name('positions');
 Route::get('/token', [\App\Http\Controllers\ApiController::class, 'getUserToken'])->name('token');



//Route::get('/users', [\App\Http\Controllers\UserController::class, 'getAll'])->name('users');
Route::get('/create', function () { return view('user_form', ['positions' =>  Users::getPositions()]);})->name('create-form');

Route::post('/user/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create-user');
Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('edit-user');
Route::post('/user/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('update-user');
Route::post('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('delete-user');
Route::get('/users/import', [App\Http\Controllers\UserController::class, 'import'])->name('import-users');

Route::get('/api', function () { return view('documentation');})->name('api');

