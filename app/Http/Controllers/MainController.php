<?php

namespace App\Http\Controllers;

use App\Helpers\RoutesHelper;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function react()
    {
        /** @var User $user */
        $user = Auth::user();
        $userTypeCode = $user->type?->code ?? 'guest';
        return view('react', [
            'role' => $userTypeCode,
            'apiRoutes' => RoutesHelper::getApiRoutes()
        ]);
    }

    public function loadExercise(int $id) : ?Exercise{
        $exercise = Exercise::with('attachments')->where('id', $id)->get()->first() ?? null;
        if ($exercise === null) return null;
        /** @var User $user */
        $user = Auth::user();
        if ($user->type?->code !== 'student') return $exercise;
        $task = $exercise->tasks()->with('test_status')->where('user_id', $user->id)->with('file')->get()->first();
        $exercise->task = $task;
        return $exercise;
    }
}
