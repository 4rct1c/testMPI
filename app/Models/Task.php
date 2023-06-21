<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property int    $user_id
 * @property int    $exercise_id
 * @property string $first_upload_at
 * @property string $last_upload_at
 * @property string $test_status
 * @property double $mark
 * @property string $file
 *
 */
class Task extends Model
{

    public $table = 'tasks';

    public $fillable = [
        'user_id',
        'exercise_id',
        'first_upload_at',
        'last_upload_at',
        'test_status',
        'mark',
        'file'
    ];


}
