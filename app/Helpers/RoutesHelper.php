<?php

namespace App\Helpers;



class RoutesHelper
{
    public static function getApiRoutes() : array {
        return [
            'load_courses' => route(name: 'loadCourses', absolute: false),
            'load_tasks' => route(name: 'loadTasks', absolute: false),
            'load_exercise' => route(name: 'loadExercise', absolute: false),
            'upload_file' => route(name: 'uploadFile', absolute: false),
            'load_user' => route(name: 'loadUser', absolute: false),
            'update_exercise_text' => route(name: 'updateExerciseText', absolute: false),
            'load_groups' => route(name: 'loadGroups', absolute: false),
            'load_exercise_students' => route(name: 'loadExerciseStudents', absolute: false),
        ];
    }
}
