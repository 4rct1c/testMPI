<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentController extends Controller
{

    public function loadCourses(): Collection
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->group
                    ?->courses()
                    ->with(['exercises' => function($query) {
                        $query->where('is_hidden', false)
                            ->orderBy('deadline');
                    }])
                    ->get() ?? Collection::empty();
    }

    public function loadTasks(): Collection
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->tasks()->with('test_status')->get() ?? Collection::empty();
    }

    public function uploadFile(Request $request): bool
    {
        $currentDate = Carbon::now();
        $task = Task::find($request->post('task_id'));
        if ($task === null) {
            $exercise = Exercise::find($request->post('exercise_id'));
            if ($exercise === null){
                return false;
            }
            $task = new Task([
                'user_id'           => Auth::user()->id,
                'exercise_id'       => $exercise->id,
                'first_uploaded_at' => $currentDate->format('c'),
                'last_uploaded_at'  => $currentDate->format('c')
            ]);
            $task->save();
        }
        $task->file?->deleteWithFile();
        $file = $request->file('answer');
        $generatedName = Str::uuid()->toString();
        $extension = $file->extension();
        if ($extension === 'c'){
            $extension = 'cpp';
        }
        $file->storeAs( TaskFile::DIRECTORY . "$generatedName.$extension");
        return TaskFile::createRecord(
            $task,
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            $generatedName,
            $extension,
            $file->getSize(),
            $request->post('ready_for_test', 'true') === 'true',
            $currentDate
        );
    }

}
