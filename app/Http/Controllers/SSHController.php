<?php

namespace App\Http\Controllers;

use JetBrains\PhpStorm\NoReturn;
use App\Helpers\Ssh;

class SSHController extends Controller
{

    public function test(){
        self::secondAttempt();
        self::sendCommand();
    }

    private static function secondAttempt() : void
    {
        $resource = ssh2_connect('91.197.1.40', 22, 'client_to_server');
        dd($resource);

    }

    #[NoReturn] public static function sendCommand() : void
    {
        $command = 'ls';
        $ssh = Ssh::create(Ssh::USER, Ssh::HOST, Ssh::PORT);
        $ssh->usePrivateKey(Ssh::KEY_PATH);
        $ssh->removeBash();
        dump($ssh->getExecuteCommand($command));
        dump($ssh);
        $process = $ssh->execute($command);
        dump($process->getCommandLine());
        dump($process->getOutput());
        dump($process->getErrorOutput());
        dump($process->isSuccessful());
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
