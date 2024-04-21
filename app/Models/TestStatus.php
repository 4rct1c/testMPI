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
    public const COMPILATION_ERROR_STATUS = 'compilation_error';
    public const RUNTIME_ERROR_STATUS = 'runtime_error';
    public const AWAITING_CONFIRMATION_STATUS = 'awaiting_confirmation';
    public const WRONG_ANSWER = 'wrong_answer';
    public const RUNTIME_EXCEEDED = 'runtime_exceeded';
    public const SUCCESS_STATUS = 'success';

    public $table = 'test_statuses';

    public $fillable = [
        'code',
        'label',
    ];

    public static function awaitingTest() : static
    {
        return static::findOrCreateByCode(static::AWAITING_TEST_STATUS, 'Ожидает тестирования');
    }

    public static function compilationError() : static
    {
        return static::findOrCreateByCode(static::COMPILATION_ERROR_STATUS, 'Ошибка компиляции');
    }

    public static function runtimeError() : static
    {
        return static::findOrCreateByCode(static::RUNTIME_ERROR_STATUS, 'Ошибка');
    }

    public static function awaitingConfirmation() : static
    {
        return static::findOrCreateByCode(static::AWAITING_CONFIRMATION_STATUS, 'Ожидает подтверждения');
    }

    public static function wrongAnswer() : static
    {
        return static::findOrCreateByCode(static::WRONG_ANSWER, 'Неправильный ответ');
    }

    public static function runtimeExceeded() : static
    {
        return static::findOrCreateByCode(static::RUNTIME_EXCEEDED, 'Превышено время выполнения');
    }

    public static function successStatus() : static
    {
        return static::findOrCreateByCode(static::SUCCESS_STATUS, 'Успешно');
    }

    protected static function findOrCreateByCode(string $code, string $label) : static
    {
        $status = TestStatus::where('code', $code)->get()->first();
        if ($status === null){
            $status = new TestStatus(['code' => $code, 'label' => $label]);
            $status->save();
        }
        return $status;
    }



}
