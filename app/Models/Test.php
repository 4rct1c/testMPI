<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $exercise_id
 * @property string $input
 * @property string $desired_result
 * @property double $max_divergence
 * @property double $time_limit
 * @property double $overdue_multiplier
 * @property string $error_message
 *
 */
class Test extends Model
{

    public $table = 'tests';

    public $fillable = [
        'exercise_id',
        'input',
        'desired_result',
        'max_divergence',
        'time_limit',
        'overdue_multiplier',
        'error_message'
    ];


}
