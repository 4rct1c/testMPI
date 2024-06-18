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

    public const CODES_MAP = [
        self::AWAITING_TEST_STATUS          => [
            'type' => 'awaiting',
            'message' => 'Ожидает тестирования'
        ],
        self::COMPILATION_ERROR_STATUS      => [
            'type' => 'error',
            'message' => 'Ошибка компиляции'
        ],
        self::RUNTIME_ERROR_STATUS          => [
            'type' => 'error',
            'message' => 'Ошибка'
        ],
        self::AWAITING_CONFIRMATION_STATUS  => [
            'type' => 'awaiting',
            'message' => 'Ожидает подтверждения'
        ],
        self::WRONG_ANSWER                  => [
            'type' => 'wrong',
            'message' => 'Неправильный ответ'
        ],
        self::RUNTIME_EXCEEDED              => [
            'type' => 'warning',
            'message' => 'Превышено время выполнения'
        ],
        self::SUCCESS_STATUS                => [
            'type' => 'success',
            'message' => 'Успешно'
        ],
    ];


    public function isError() : bool
    {
        return static::CODES_MAP[$this->code]['type'] === 'error';
    }

    public function isWrong() : bool
    {
        return static::CODES_MAP[$this->code]['type'] === 'wrong';
    }

    public function isWarning() : bool
    {
        return static::CODES_MAP[$this->code]['type'] === 'warning';
    }

    public function isSuccessful() : bool
    {
        return static::CODES_MAP[$this->code]['type'] === 'success';
    }

    public function isAwaiting() : bool
    {
        return static::CODES_MAP[$this->code]['type'] === 'awaiting';
    }

    public static function awaitingTest() : static
    {
        $code = static::AWAITING_TEST_STATUS;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function compilationError() : static
    {
        $code = static::COMPILATION_ERROR_STATUS;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function runtimeError() : static
    {
        $code = static::RUNTIME_ERROR_STATUS;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function awaitingConfirmation() : static
    {
        $code = static::AWAITING_CONFIRMATION_STATUS;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function wrongAnswer() : static
    {
        $code = static::WRONG_ANSWER;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function runtimeExceeded() : static
    {
        $code = static::RUNTIME_EXCEEDED;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function successStatus() : static
    {
        $code = static::SUCCESS_STATUS;
        return static::findOrCreateByCode($code, static::CODES_MAP[$code]['message']);
    }

    public static function findOrCreateByCode(string $code, string $label = "") : static
    {
        $status = TestStatus::where('code', $code)->get()->first();
        if ($status === null){
            $label = mb_strlen($label) ? $label : (static::CODES_MAP[$code] ?? "");
            $status = new TestStatus(['code' => $code, 'label' => $label]);
            $status->save();
        }
        return $status;
    }



}
