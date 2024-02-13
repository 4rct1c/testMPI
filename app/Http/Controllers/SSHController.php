<?php

namespace App\Http\Controllers;

use JetBrains\PhpStorm\NoReturn;
use App\Helpers\Ssh;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SSHController extends Controller
{

    public function test(){
        self::secondAttempt();
        self::sendCommand();
    }


    public static function secondAttempt() : void {
//        $command = ["ssh -p 22 -i C:\Users\baika\.ssh\mpi-test Arct1cW@91.197.1.40 'bash -se'", "ls"];
        $command = ["ssh -p 22 -i /var/www/.ssh/veesp Arct1cW@91.197.1.40 'bash -se'", "ls"];
        $process = new Process($command);
        try {
            $process->mustRun();

            // Get the command output
            $output = $process->getOutput();

            dump('yes');
            dd($output);
        } catch (ProcessFailedException $e) {
            // Handle the exception...
            $errorMessage = $e->getMessage();
            dump('no');
            dump($process);
            dump($errorMessage);
        }
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
