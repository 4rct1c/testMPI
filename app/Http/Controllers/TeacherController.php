<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Group;
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
            $students_count = count($group->students);
            $groupBelongsToCurrentUser = true;
            /** @var Course $course */
            foreach ($group->courses as $course){
                if ($course->teacher_id !== Auth::user()->id){
                    $groupBelongsToCurrentUser = false;
                    break;
                }
                /** @var Exercise $exercise */
                foreach ($course->exercises as $exercise){
                    $exercise->loaded_tasks = count($exercise->tasks);
                    $exercise->failed_tasks = count($exercise->failedTasks());
                    $exercise->succeeded_tasks = count($exercise->succeededTasks());
                    $exercise->awaiting_tasks = count($exercise->awaitingTasks());
                    $exercise->students_count = $students_count;
                }
            }
            if ($groupBelongsToCurrentUser){
                $result[] = $group;
            }
        }
        return $result;
    }

}
