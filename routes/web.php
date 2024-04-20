<?php

use App\Http\Controllers\MainController;
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

Route::get('/', function () {return redirect(\route('reactWithoutRole'));})->name('default');

Route::get('/portal/', [MainController::class, 'reactWithoutRole'])->name('reactWithoutRole');
Route::get('/portal/{role}/{path?}/', [MainController::class, 'react'])->name('react');

//Route::get('/ssh/test/', [SSHController::class, 'test'])->name('testSSH');
Route::get('/api/courses/load/', [StudentController::class, 'loadCourses'])->name('loadCourses');


//Route::get('/home', [HomeController::class, 'index'])->name('home');
