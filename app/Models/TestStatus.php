<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin Builder
 *
 * @property int        $id
 * @property string     $code
 * @property string     $label
 *
 */
class TestStatus extends Model
{

    public const AWAITING_TEST_STATUS = 'awaiting_test';

    public $table = 'test_statuses';

    public $fillable = [
        'code',
        'label',
    ];



}
