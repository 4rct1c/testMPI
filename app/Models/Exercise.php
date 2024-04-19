<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $course_id
 * @property string $title
 * @property int    $max_score
 * @property string $deadline
 * @property double $deadline_multiplier
 *
 */
class Exercise extends Model
{

    public $table = 'exercises';

    public $fillable = [
        'course_id',
        'title',
        'max_score',
        'deadline',
        'deadline_multiplier',
    ];


}
