<?php

namespace App\Models;

use App\Helpers\SshHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property string $host
 * @property string $username
 * @property int    $port
 * @property string $key_name
 * @property string $files_directory
 * @property int    $frequency_minutes
 * @property int    $batch_size
 *
 */
class Cluster extends Model
{

    public $table = 'courses';

    public $fillable = [
        'host',
        'username',
        'port',
        'key_name',
        'files_directory',
        'frequency_minutes',
        'batch_size',
    ];

    public function getKeyPath() : string
    {
        return SshHelper::KEYS_DIRECTORY . $this->key_name;
    }


}
