<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int     $id
 * @property string  $channel
 * @property string  $level
 * @property string  $message
 * @property ?array  $context
 * @property ?string $extra
 */
class LogRecord extends Model
{

    public $table = 'log_records';

    public $fillable = [
        'channel',
        'level',
        'message',
        'context',
        'extra',
    ];

    protected $casts = [
        'context' => 'array',
        'extra'   => 'array',
    ];


}
