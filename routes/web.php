<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\SSHController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\HomeController;

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

Route::get('/{path?}', [MainController::class, 'react'])->name('react');

Route::get('/ssh/test/', [SSHController::class, 'test'])->name('testSSH');

Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');
