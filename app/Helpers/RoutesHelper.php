<?php

namespace App\Helpers;


use JetBrains\PhpStorm\ArrayShape;

class RoutesHelper
{
    public static function getApiRoutes() : array {
        return [
            'load_courses' => route(name: 'loadCourses', absolute: false),
            'load_tasks' => route(name: 'loadTasks', absolute: false),
            'load_exercise' => route(name: 'loadExercise', absolute: false),
            'upload_file' => route(name: 'uploadFile', absolute: false),
        ];
    }
}
