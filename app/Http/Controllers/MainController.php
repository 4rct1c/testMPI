<?php

namespace App\Http\Controllers;

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

    public function react(string $role)
    {
        /** @var User $user */
        $user = Auth::user();
        $userTypeCode = $user->type?->code ?? 'guest';
        if ($userTypeCode !== $role){
            if ($user->isAdmin()){
                return view('react', [
                    'role' => $role
                ]);
            }
            if ($role === 'guest'){
                return redirect(route('react', ['role' => $userTypeCode]));
            }
            abort(403, 'Forbidden. Wrong role.');
        }
        return view('react', [
            'role' => $userTypeCode
        ]);
    }
}
