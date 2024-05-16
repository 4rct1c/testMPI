<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{

    public function updateExerciseText(Request $request) : bool {
        $exercise = Exercise::find($request->post('id'));
        if ($exercise === null) {
            return false;
        }
        $exercise->text = $request->post('text');
        return $exercise->save();
    }

    public function loadGroups() : array {
        $groups = Group::with('courses')->get();
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
