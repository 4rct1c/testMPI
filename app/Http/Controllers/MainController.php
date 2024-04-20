<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
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

    public function react(string $role)
    {
        /** @var User $user */
        $user = Auth::user();
        $userTypeCode = $user->type?->code ?? 'guest';
        if (!$user->isAdmin() && $userTypeCode !== $role){
            abort(403, 'Forbidden. Wrong role.');
        }
        return view('react', [
            'role' => $userTypeCode
        ]);
    }
}
