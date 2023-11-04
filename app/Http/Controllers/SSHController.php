<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use JetBrains\PhpStorm\NoReturn;
use App\Helpers\Ssh;

class SSHController extends Controller
{

    public function test(){
        self::sendCommand();
    }

    #[NoReturn] public static function sendCommand() : void
    {
        $user = 'Arct1cW';
        $host = '91.197.1.40';
//        $pkey = '/Users/baika/.ssh/mpi-test';
        $pkey = 'C:\Users\baika\.ssh\mpi-test';
        $port = 22;
        $ssh = Ssh::create($user, $host, $port);
        $ssh->usePrivateKey($pkey);
        $ssh->removeBash();
        dump($ssh->getExecuteCommand('ls -la'));
        dump($ssh);
        $process = $ssh->execute('ls -la');
        dump($process->getOutput());
        dump($process->getErrorOutput());
        dump($process->isSuccessful());
        dd($process);
    }
}
