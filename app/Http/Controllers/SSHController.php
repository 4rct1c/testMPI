<?php

namespace App\Http\Controllers;

use App\Jobs\FilesSendDispatcherJob;
use Illuminate\Support\Facades\Auth;

class SSHController extends Controller
{

    public function dispatchJob(){
        if (!Auth::user()->isAdmin()){
            abort(403);
        }
        FilesSendDispatcherJob::dispatch(1);
    }
}
