<?php

namespace App\Helpers;

use Spatie\Ssh\Ssh as OriginalSsh;

class Ssh extends OriginalSsh
{


    /**
     * @param string|array $command
     *
     * @return string
     */
    public function getExecuteCommand($command): string
    {
        $commands = $this->wrapArray($command);

        $extraOptions = implode(' ', $this->getExtraOptions());

        $commandString = implode(PHP_EOL, $commands);

        $target = $this->getTargetForSsh();

        if (in_array($this->host, ['local', 'localhost', '127.0.0.1'])) {
            return $commandString;
        }

        $bash = $this->addBash ? "'bash -se'" : '';

        return "ssh {$extraOptions} {$target} {$bash}".PHP_EOL
            .$commandString.PHP_EOL;
    }
}
