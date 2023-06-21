<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $exercise_id
 * @property string $input
 * @property string $desired_output
 * @property double $time_limit
 * @property string $error_message
 *
 */
class Test extends Model
{

    public $table = 'tests';

    public $fillable = [
        'exercise_id',
        'input',
        'desired_output',
        'time_limit',
        'error_message'
    ];


}
