<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseAttachment extends Model
{

    public $table = 'exercises_attachments';

    public $fillable = [
        'exercise_id',
        'file_path',
    ];

    public function Exercise() : BelongsTo{
        return $this->belongsTo(Exercise::class);
    }

}
