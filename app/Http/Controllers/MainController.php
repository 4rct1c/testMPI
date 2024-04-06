<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    public function react()
    {
        /** @var User $user */
        $user = Auth::user();
        $userTypeCode = $user->getTypeCode();
        return view('react', [
            'role' => $userTypeCode
        ]);
    }
}
