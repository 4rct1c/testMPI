<?php

namespace App\Helpers;


use JetBrains\PhpStorm\ArrayShape;

class RoutesHelper
{
    #[ArrayShape(['load_courses' => "string", 'load_tasks' => "string"])]
    public static function getApiRoutes() : array {
        return [
            'load_courses' => route(name: 'loadCourses', absolute: false),
            'load_tasks' => route(name: 'loadTasks', absolute: false),
        ];
    }
}
