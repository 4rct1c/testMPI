<?php

namespace App\Monolog;

use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\Handler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Throwable;

class DataBaseLogHandler extends Handler
{

    private int $level;

    protected ?Model $owner;


    public function __construct(Level $level, ?Model $owner = null)
    {
        $this->owner = $owner;
        $this->level = Logger::toMonologLevel($level)->value;
    }

    /**
     * @inheritDoc
     */
    public function isHandling(LogRecord $record): bool
    {
        return $record['level'] >= $this->level;
    }

    /**
     * @inheritDoc
     */
    public function handle(LogRecord $record): bool
    {
        if ($record['level'] < $this->level)
        {
            return false;
        }

        try
        {
            $logRecord = new \App\Models\LogRecord([
                'channel'   => $record->channel,
                'level'     => $record->level->getName(),
                'message'   => $record->message,
                'context'   => $record->context,
                'extra'     => $record->extra,
            ]);
            $logRecord->level = $record['level_name'];

            if ($this->owner)
            {
                $logRecord->owner()->associate($this->owner);
            }
            $result = $logRecord->saveQuietly();
        }
        catch (Throwable)
        {
            return false;
        }

        return $result;
    }
}
