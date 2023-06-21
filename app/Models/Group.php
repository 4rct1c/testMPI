<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 *
 */
class Group extends Model
{

    public $table = 'groups';

    public $fillable = [
        'code',
        'name'
    ];


}
