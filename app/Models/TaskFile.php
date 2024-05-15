<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin Builder
 *
 * @property int    $id
 * @property int    $task_id
 * @property string $original_name
 * @property string $generated_name
 * @property string $extension
 * @property int    $size
 * @property string $created_at
 * @property string $updated_at
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
        $directory = 'answers/';
        $path = $directory . $this->generatedNameWithExtension();
        if (Storage::exists($path)){
            if (Storage::delete($path)){
                return $this->delete();
            }
            return false;
        }
        return false;
    }
}
