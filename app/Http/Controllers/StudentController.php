<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function loadExercises() : Collection {
        /** @var User $user */
        $user = Auth::user();
        return $user->getExercises() ?? Collection::empty();
    }

    public function uploadAnswer() : bool {
        return false;
    }

}
