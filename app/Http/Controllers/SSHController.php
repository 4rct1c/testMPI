<?php

namespace App\Http\Controllers;

use App\Jobs\FilesSendDispatcherJob;

class SSHController extends Controller
{

    public function dispatchJob(){
        FilesSendDispatcherJob::dispatch(1);
    }
}
