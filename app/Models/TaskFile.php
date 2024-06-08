<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin Builder
 *
 * @property int        $id
 * @property int        $task_id
 * @property string     $original_name
 * @property string     $generated_name
 * @property string     $extension
 * @property int        $size
 * @property boolean    $ready_for_test
 * @property string     $created_at
 * @property string     $updated_at
 *
 */
class TaskFile extends Model
{

    public const DIRECTORY = 'tasks/';

    public $table = 'tasks_files';

    public $fillable = [
        'task_id',
        'original_name',
        'generated_name',
        'extension',
        'size',
        'ready_for_test',
    ];

    public function generatedNameWithExtension() : string
    {
        return $this->generated_name . "." . $this->extension;
    }

    public function originalNameWithExtension() : string
    {
        return $this->original_name . "." . $this->extension;
    }


    public function deleteWithFile() : bool {
        $path = static::DIRECTORY . $this->generatedNameWithExtension();
        if (Storage::exists($path)){
            if (Storage::delete($path)){
                return $this->delete();
            }
            return false;
        }
        return false;
    }

    public static function createRecord(
        Task $task,
        string $originalName,
        string $generatedName,
        string $extension,
        int $size,
        bool $readyForTest,
        ?Carbon $lastUploadedDate = null
    ) : bool
    {
        if ($lastUploadedDate === null){
            $lastUploadedDate = Carbon::now();
        }
        try{
            $taskFile = new TaskFile([
                'task_id'        => $task->id,
                'original_name'  => $originalName,
                'generated_name' => $generatedName,
                'extension'      => $extension,
                'size'           => $size,
                'ready_for_test' => $readyForTest,
            ]);
            $fileAdded = $taskFile->save();
            if ($fileAdded){
                $task->test_status_id = TestStatus::awaitingTest()->id;
                if ($task->last_uploaded_at !== $lastUploadedDate->format('c')){
                    $task->last_uploaded_at = $lastUploadedDate->format('c');
                }
                $task->save();
            }
            return $fileAdded;
        } catch (\Exception $e){
            Log::warning("Failed to upload file. Original exception: " . $e->getMessage());
            return false;
        }
    }
}
