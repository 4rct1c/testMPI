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
    ];


    public static function byDeadline(bool $missed = false) : Collection
    {
        return static::whereDate('deadline', $missed ? '<' : '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('deadline')
            ->get();
    }


    public function awaitingTasks() : Collection
    {
        return $this->tasksByTestStatus(TestStatus::AWAITING_TEST_STATUS);
    }


    public function succeededTasks() : Collection
    {
        return $this->tasksByTestStatus(TestStatus::SUCCESS_STATUS);
    }


    public function failedTasks() : Collection
    {
        $result = $this->tasksByTestStatus(TestStatus::WRONG_ANSWER);
        $result->merge($this->tasksByTestStatus(TestStatus::COMPILATION_ERROR_STATUS));
        $result->merge($this->tasksByTestStatus(TestStatus::RUNTIME_ERROR_STATUS));
        $result->merge($this->tasksByTestStatus(TestStatus::RUNTIME_EXCEEDED));
        return $result;
    }


    public function tasksByTestStatus(string $testStatus) : Collection
    {
        $statusRecord = TestStatus::where('code', $testStatus)->get()->first();
        if ($statusRecord === null) {
            Log::error("Test status '$testStatus' isn't set");
            return Collection::empty();
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
