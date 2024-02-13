<?php

namespace App\Http\Controllers;

use JetBrains\PhpStorm\NoReturn;
use App\Helpers\Ssh;

class SSHController extends Controller
{

    public function test(){
        self::sendCommand();
    }

    #[NoReturn] public static function sendCommand() : void
    {
        $command = 'ls';
        $ssh = new Ssh(Ssh::USER, Ssh::HOST, Ssh::PORT);
        $ssh->usePrivateKey(Ssh::KEY_PATH);
//        $ssh->removeBash();
        $process = $ssh->execute($command);
        dump($process->getCommandLine());
        dump($process->getOutput());
        dump($process->getErrorOutput());
        dump($process->isSuccessful());
        dump($process->getExitCode());
        dump($process->getExitCodeText());
        dump($process->getStatus());
//        dump($process->getTermSignal());
        dump($process->getEnv());
        dd($process);
    }

    private static function binaryToString($binaryInput): ?string
    {
        $binaries = explode(' ', $binaryInput);

        $string = null;
        foreach ($binaries as $binary) {
            $string .= pack('H*', dechex(bindec($binary)));
        }

        return $string;
    }
}
