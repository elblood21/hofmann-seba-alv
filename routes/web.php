<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', [TestController::class, 'home'])->name('home');
Route::get('/getUser/{id}', [TestController::class, 'getUser'])->name('getUser');
Route::post('/updateUser', [TestController::class, 'updateUser'])->name('updateUser');
