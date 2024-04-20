<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function loadCourses() : Collection {
        /** @var User $user */
        $user = Auth::user();
        return $user->group
                ?->courses()
                ->with('exercises')
                ->get() ?? Collection::empty();
    }
    public function loadTasks() : Collection {
        /** @var User $user */
        $user = Auth::user();
        return $user->tasks ?? Collection::empty();
    }

    public function uploadAnswer() : bool {
        return false;
    }

}
