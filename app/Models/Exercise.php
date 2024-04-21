<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

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


    public static function byDeadline(bool $missed = false) : Collection
    {
        return static::whereDate('deadline', $missed ? '<' : '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('deadline')
            ->get();
    }


    public function awaitingTasks() : Collection
    {
        $awaitingStatus = TestStatus::where('code', TestStatus::AWAITING_TEST_STATUS)->get()->first();
        if ($awaitingStatus === null) {
            Log::error("Awaiting test status isn't set");
            return Collection::empty();
        }
        return $this->tasks()->where('test_status_id', $awaitingStatus->id)->orderBy('last_uploaded_at')->get();
    }


    public function tasks() : HasMany
    {
        return $this->hasMany(Task::class);
    }


    public function attachments() : HasMany
    {
        return $this->hasMany(ExerciseAttachment::class);
    }
}
