<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 *
 * @property string $code
 * @property string $name
 *
 */
class UserType extends Model
{

    public $table = 'user_types';

    public $fillable = [
        'code',
        'name'
    ];


}
