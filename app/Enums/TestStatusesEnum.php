<?php

namespace App\Enums;


/**
 * По хорошему надо будет перейти на этот енам вместо отдельной таблицы, так как так проще.
 */
enum TestStatusesEnum: string
{
    case Awaiting = 'awaiting_test';
    case CompilationError = 'compilation_error';
    case RuntimeError = 'runtime_error';
    case AwaitingConfirmation = 'awaiting_confirmation';
    case WrongAnswer = 'wrong_answer';
    case RuntimeExceeded = 'runtime_exceeded';
    case Success = 'success';
    case ManualMark = 'manual_mark';


    public function description() : string
    {
        return match ($this) {
            self::Awaiting             => "Ожидает тестирования",
            self::CompilationError     => "Ошибка компиляции",
            self::RuntimeError         => "Ошибка выполнения",
            self::AwaitingConfirmation => "Ожидает готовности",
            self::WrongAnswer          => "Неправильный ответ",
            self::RuntimeExceeded      => "Превышено время выполнения",
            self::Success              => "Успешно",
            self::ManualMark           => "Оценка выставлена преподавателем",
        };
    }

    public function isError() : bool
    {
        return match($this) {
            self::CompilationError, self::RuntimeError => true,
            default => false,
        };
    }

    public function isWrong() : bool
    {
        return $this === self::WrongAnswer;
    }

    public function isWarning() : bool
    {
        return $this === self::RuntimeExceeded;
    }

    public function isSuccessful() : bool
    {
        return match($this) {
            self::Success, self::ManualMark => true,
            default => false,
        };
    }

    public function isAwaiting() : bool
    {
        return match($this) {
            self::Awaiting, self::AwaitingConfirmation => true,
            default => false,
        };
    }
}
