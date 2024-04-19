<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\SSHController;
use App\Http\Controllers\StudentController;
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
Auth::routes();

Route::get('/{path?}', [MainController::class, 'react'])->name('react');

Route::get('/ssh/test/', [SSHController::class, 'test'])->name('testSSH');
Route::get('/api/exercises/load/', [StudentController::class, 'loadExercises'])->name('loadExercises');


//Route::get('/home', [HomeController::class, 'index'])->name('home');
