<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $teacher_id
 * @property string $name
 *
 */
class Course extends Model
{

    public $table = 'courses';

    public $fillable = [
        'group_id',
        'teacher_id',
        'name'
    ];


}
