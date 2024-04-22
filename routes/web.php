<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::get('/', function () {return redirect(\route('react'));})->name('default');

//Route::get('/portal/', [MainController::class, 'reactWithoutRole'])->name('reactWithoutRole');
Route::get('/portal/{path?}', [MainController::class, 'react'])
    ->where('path', '.*')->name('react');

//Route::get('/ssh/test/', [SSHController::class, 'test'])->name('testSSH');
Route::get('/api/courses/load/', [StudentController::class, 'loadCourses'])->name('loadCourses');
Route::get('/api/tasks/load/', [StudentController::class, 'loadTasks'])->name('loadTasks');
Route::post('/api/file/upload/', [StudentController::class, 'uploadFile'])->name('uploadFile');

Route::get('/api/exercise/load/{id?}', [MainController::class, 'loadExercise'])->name('loadExercise');
Route::get('/api/user/load/', [MainController::class, 'loadUser'])->name('loadUser');

Route::put('/api/exercise/update/', [TeacherController::class, 'updateExerciseText'])->name('updateExerciseText');

//Route::get('/home', [HomeController::class, 'index'])->name('home');
