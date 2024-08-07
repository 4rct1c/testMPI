<?php

namespace App\Models;

use App\Enums\TestStatusesEnum;
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
 * @property boolean            $is_hidden
 * @property Collection<Task>   $tasks
 * @property Collection<Test>   $tests
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
        'is_hidden',
    ];


    public static function byDeadline(bool $missed = false) : Collection
    {
        return static::whereDate('deadline', $missed ? '<' : '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('deadline')
            ->get();
    }

    public static function withoutDeadline() : Collection
    {
        return static::whereNull('deadline')->orderBy('created_at')->get();
    }


    public function awaitingTasks() : Collection
    {
        return $this->tasksByTestStatus(TestStatusesEnum::Awaiting);
    }


    public function succeededTasks() : Collection
    {
        return $this->tasksByTestStatus(TestStatusesEnum::Success);
    }


    public function warningTasks() : Collection
    {
        return $this->tasksByTestStatus(TestStatusesEnum::RuntimeExceeded);
    }



    public function failedTasks() : Collection
    {
        $result = $this->tasksByTestStatus(TestStatusesEnum::WrongAnswer);
        $result = $result->merge($this->tasksByTestStatus(TestStatusesEnum::CompilationError));
        return $result->merge($this->tasksByTestStatus(TestStatusesEnum::RuntimeError));
    }


    public function tasksByTestStatus(TestStatusesEnum $testStatusEnum) : Collection
    {
        $statusRecord = TestStatus::where('code', $testStatusEnum->value)->get()->first();
        if ($statusRecord === null) {
            Log::debug("Exercise: creating test status with code '$testStatusEnum->value'");
            $statusRecord = TestStatus::findOrCreateByEnum($testStatusEnum);
        }
        return $this->tasks()->where('test_status_id', $statusRecord->id)->orderBy('last_uploaded_at')->get();
    }


    public function students() : Collection
    {
        $result = new Collection();
        foreach ($this->tasks as $task){
            $user = $task->user;
            $user->full_name = $user->fullName();
            $result->push($user);
        }
        return $result;
    }


    public function tasks() : HasMany
    {
        return $this->hasMany(Task::class);
    }


    public function tests() : HasMany
    {
        return $this->hasMany(Test::class);
    }


    public function attachments() : HasMany
    {
        return $this->hasMany(ExerciseAttachment::class);
    }
}
