<?php

namespace App\Models;

use App\Enums\TestStatusesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * В последствии нужно будет отказаться от этой модели и таблице в принципе и перейти на enum с записью кода в таблице заданий
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

    public $table = 'test_statuses';

    public $fillable = [
        'code',
        'label',
    ];

    public function getEnum() : TestStatusesEnum
    {
        return TestStatusesEnum::from($this->code);
    }

    public static function findOrCreateByEnum(TestStatusesEnum $enum) : static
    {
        $status = TestStatus::where('code', $enum->value)->get()->first();
        if ($status === null){
            $label = $enum->description() ?? null;
            $status = new TestStatus(['code' => $enum->value, 'label' => $label]);
            $status->save();
        }
        return $status;
    }



}
