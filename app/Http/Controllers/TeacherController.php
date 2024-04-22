<?php

namespace App\Http\Controllers;

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
        foreach ($groups as $group) {
            if (!count($group->courses)){
                continue;
            }
            $groupBelongsToCurrentUser = true;
            foreach ($group->courses as $course){
                if ($course->teacher_id !== Auth::user()->id){
                    $groupBelongsToCurrentUser = false;
                    break;
                }
                // Нужно,чтобы подтянуть данные об exercises
                $course->exercises;
            }
            if ($groupBelongsToCurrentUser){
                $result[] = $group;
            }
        }
        return $result;
    }

}
