<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $teacher_id
 * @property string $name
 *
 */
class Course extends Model
{

    public $table = 'course';

    public $fillable = [
        'group_id',
        'teacher_id',
        'name'
    ];


}
