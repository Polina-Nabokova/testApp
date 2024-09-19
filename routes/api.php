<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::post('/usersq', function (Request $request) {
//    return $request;
//});

// api routes
 Route::post('/users', [\App\Http\Controllers\ApiController::class, 'createUser'])->name('create');

 Route::get('/users/', [\App\Http\Controllers\ApiController::class, 'getUsers'])->name('users');
 Route::get('/users/{id}', [\App\Http\Controllers\ApiController::class, 'getOneUser'])->name('user');
 Route::get('/positions', [\App\Http\Controllers\ApiController::class, 'getPositions'])->name('positions');
 Route::get('/token', [\App\Http\Controllers\ApiController::class, 'getUserToken'])->name('token');