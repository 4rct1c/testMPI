<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\SSHController;
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

Route::get('/portal/{path?}', [MainController::class, 'react'])
    ->where('path', '.*')->name('react');

Route::get('/job/dispatch/', [SSHController::class, 'dispatchJob'])->name('dispatchJob');

Route::group([
    'prefix' => 'api'
], function () {
    Route::get('/courses/load/', [StudentController::class, 'loadCourses'])->name('loadCourses');
    Route::get('/tasks/load/', [StudentController::class, 'loadTasks'])->name('loadTasks');
    Route::post('/file/upload/', [StudentController::class, 'uploadFile'])->name('uploadFile');

    Route::get('/exercise/load/{id?}', [MainController::class, 'loadExercise'])->name('loadExercise');
    Route::get('/user/load/', [MainController::class, 'loadUser'])->name('loadUser');

    Route::get('/exercise/load-students/{id?}', [TeacherController::class, 'loadExerciseStudents'])->name('loadExerciseStudents');
    Route::put('/exercise/update/', [TeacherController::class, 'updateExercise'])->name('updateExercise');
    Route::get('/groups/load/', [TeacherController::class, 'loadGroups'])->name('loadGroups');
    Route::post('/test/add/', [TeacherController::class, 'addTest'])->name('addTest');
    Route::put('/test/update/', [TeacherController::class, 'updateTest'])->name('updateTest');
});

