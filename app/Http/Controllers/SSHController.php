<?php

namespace App\Http\Controllers;

use App\Jobs\FilesSendDispatcherJob;
use App\Models\Cluster;
use JetBrains\PhpStorm\NoReturn;
use App\Helpers\Ssh;

class SSHController extends Controller
{

    public function dispatchJob(){
        FilesSendDispatcherJob::dispatch(1);
    }
}
