<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $teacher_id
 * @property string $name
 * @property Collection<Exercise> $exercises
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

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }


}
