<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 *
 * @property Collection<Course> $courses
 *
 */
class Group extends Model
{

    public $table = 'groups';

    public $fillable = [
        'code',
        'name'
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }


}
