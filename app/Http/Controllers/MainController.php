<?php

namespace App\Http\Controllers;

use App\Helpers\RoutesHelper;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    public function reactWithoutRole()
    {
        /** @var User $user */
        $user = Auth::user();
        $userTypeCode = $user->type?->code ?? 'guest';
        return redirect(route('react', ['role' => $userTypeCode]));
    }

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
        return Exercise::find($id);
    }
}
