<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Group;
use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{

    public function updateExercise(Request $request) : bool {
        $exercise = Exercise::find($request->post('id'));
        if ($exercise === null) {
            if ($request->post('course_id') === null){
                return false;
            }
            $exercise = new Exercise();
            $exercise->course_id = $request->post('course_id');
        }
        $exercise->title = $request->post('title');
        $exercise->max_score = $request->post('max_score');
        $exercise->deadline = $request->post('deadline');
        $exercise->deadline_multiplier = $request->post('deadline_multiplier');
        $exercise->text = $request->post('text');
        $exercise->is_hidden = $request->post('is_hidden');
        return $exercise->save();
    }

    public function deleteExercise(int $exerciseId) : bool {
        $exercise = Exercise::find($exerciseId);
        if ($exercise === null){
            return false;
        }
        return $exercise->delete() ?? false;
    }

    public function loadGroups() : array {
        $groups = Group::with(['courses' => function($query) {
            $query->with(['exercises' => function($query) {
                $query->orderBy('deadline');
            }]);
        }])->get();
        $result = [];
        /** @var Group $group */
        foreach ($groups as $group) {
            if (!count($group->courses)){
                continue;
            }
            $studentsCount = count($group->students);
            /** @var Course $course */
            foreach ($group->courses as $course){
                if ($course->teacher_id !== Auth::user()->id){
                    continue;
                }
                /** @var Exercise $exercise */
                foreach ($course->exercises as $key => $exercise){
                    $course->exercises->put($key, static::addAttributesToExercise($exercise, $studentsCount));
                }
            }
            $result[] = $group;
        }
        return $result;
    }

    public function loadExerciseStudents(?int $exerciseId = null): Collection
    {
        if ($exerciseId === null){
            return Collection::make();
        }
        $exercise = Exercise::find($exerciseId);
        if ($exercise === null){
            return Collection::make();
        }
        return $exercise->students();
    }

    public function addTest(Request $request) : bool
    {
        $test = new Test();
        $test->exercise_id = $request->post('exercise_id');
        $test = static::fillTest($test, $request);
        return $test->save();
    }

    public function updateTest(Request $request) : bool
    {
        $test = Test::find($request->post('id'));
        if ($test === null){
            return false;
        }
        $test = static::fillTest($test, $request);
        return $test->save();
    }

    private static function fillTest(Test $test, Request $request) : Test{
        $test->input = $request->post('input');
        $test->desired_result = $request->post('desired_result');
        $test->max_divergence = $request->post('max_divergence');
        $test->time_limit = $request->post('time_limit');
        $test->overdue_multiplier = $request->post('overdue_multiplier');
        $test->error_message = $request->post('error_message');
        return $test;
    }

    private static function addAttributesToExercise(Exercise $exercise, int $studentsCount) : Exercise
    {
        $exercise->loaded_tasks = count($exercise->tasks);
        $exercise->failed_tasks = count($exercise->failedTasks());
        $exercise->succeeded_tasks = count($exercise->succeededTasks());
        $exercise->awaiting_tasks = count($exercise->awaitingTasks());
        $exercise->students_count = $studentsCount;
        foreach ($exercise->tasks as $task){
            $task->file;
            $task->test_status;
        }
        return $exercise;
    }

}
