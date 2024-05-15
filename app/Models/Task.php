<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin Builder
 *
 * @property int        $id
 * @property int        $user_id
 * @property int        $exercise_id
 * @property string     $first_uploaded_at
 * @property string     $last_uploaded_at
 * @property double     $mark
 * @property string     $comment
 * @property string     $teacher_comment
 * @property int        $test_status_id
 * @property string     $test_message
 * @property TaskFile   $file
 * @property TestStatus $test_status
 * @property Exercise   $exercise
 * @property User       $user
 *
 */
class Task extends Model
{

    public $table = 'tasks';

    public $fillable = [
        'user_id',
        'exercise_id',
        'first_uploaded_at',
        'last_uploaded_at',
        'mark',
        'file',
        'comment',
        'teacher_comment',
        'test_status_id',
        'test_message',
    ];

    public function file() : HasOne {
        return $this->hasOne(TaskFile::class, 'task_id', 'id');
    }

    public function exercise() : BelongsTo {
        return $this->belongsTo(Exercise::class);
    }

    public function test_status() : BelongsTo {
        return $this->belongsTo(TestStatus::class, 'test_status_id', 'id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }




}
