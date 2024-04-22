<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

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

}
