<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int                $id
 * @property int                $course_id
 * @property string             $title
 * @property int                $max_score
 * @property string             $deadline
 * @property double             $deadline_multiplier
 * @property string             $text
 * @property Collection<Task>   $tasks
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
        'text',
    ];


    public function tasks() : HasMany
    {
        return $this->hasMany(Task::class);
    }


    public function attachments() : HasMany
    {
        return $this->hasMany(ExerciseAttachment::class);
    }
}
