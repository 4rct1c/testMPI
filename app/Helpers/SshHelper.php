<?php

namespace App\Helpers;

use App\Models\Cluster;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\Test;
use App\Models\TestStatus;
use Illuminate\Support\Facades\Log;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

class SshHelper
{

    public const KEYS_DIRECTORY = '/var/www/.ssh/';

    protected TaskFile $file;
    protected Task $task;

    public function __construct(protected Cluster $cluster, Task|TaskFile $taskOrTaskFile)
    {
        if ($taskOrTaskFile instanceof Task){
            $this->task = $taskOrTaskFile;
            $file = $this->task->file;
            if ($file !== null) {
                $this->file = $file;
            }
        }
        else {
            $this->file = $taskOrTaskFile;
            $this->task = $this->file->task;
        }
    }

    public function createSshCommand() : Ssh {
        return Ssh::create($this->cluster->username, $this->cluster->host, $this->cluster->port)
            ->setTimeout(60)
            ->usePrivateKey($this->cluster->getKeyPath());
    }

    public function handleCompilationError(string $errorMessage) : void
    {
        $this->task->test_status_id = TestStatus::compilationError()->id;
        $this->task->test_message = $errorMessage;
        $this->task->save();
    }

    public function runTests() : void
    {
        $multiplier = 1;
        $finalMessage = "";
        /** @var Test $test */
        foreach ($this->task->exercise->tests as $test){
            $process = $this->executeFile($test);
            $testResult = $this->handleExecuteResponse($process, $test);
            if ($testResult->status->isSuccessful()){
                Log::debug("Test " . $test->id . ". Result success.");
                continue;
            }

            if ($testResult->status->isError() || $testResult->status->isWrong()){
                Log::debug("Test " . $test->id . ". Result " . ($testResult->status->isError() ? "error" : "wrong") . ".");
                $this->task->test_status_id = $testResult->status->id;
                $this->task->test_message = $testResult->message;
                $this->task->save();
                return;
            }
            if ($testResult->status->isWarning()){
                Log::debug("Test " . $test->id . ". Result warning.");
                $multiplier *= $test->overdue_multiplier;
                $finalMessage .= $testResult->message . "\n";
            }
        }
        if ($multiplier !== 1){
            $this->task->test_status_id = TestStatus::runtimeExceeded()->id;
            $this->task->test_message = $finalMessage;
            $this->task->mark = $this->task->exercise->max_score * $multiplier;
            $this->task->save();
            Log::debug("Final result warning.");
            return;
        }
        $this->task->test_status_id = TestStatus::successStatus()->id;
        $this->task->test_message = "Тесты пройдены успешно";
        $this->task->mark = $this->task->exercise->max_score;
        $this->task->save();
        Log::debug("Final result success.");

    }

    public function executeFile(?Test $test = null) : Process
    {
        $executeCommand = $this->getExecutionCommand();
        if ($test !== null){
            $executeCommand .= " " . $test->input;
        }
        return $this->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $executeCommand
        ]);
    }

    public function getCompileCommand() : string
    {
        return "mpiCC " . $this->file->generatedNameWithExtension() . " -o " . $this->file->generated_name;
    }

    public function getExecutionCommand() : string
    {
        return "mpirun -np " . $this->cluster->processors_count . " ./" . $this->file->generated_name;
    }

    protected function handleExecuteResponse(Process $process, ?Test $test = null) : TestResult
    {
        if (!$process->isSuccessful()){
            Log::debug('Helper: execution error in file ' . $this->file->originalNameWithExtension() .
                ' (id ' . $this->file->id . ')' .
                "\nStatus: " . $process->getStatus() .
                "\nError output: " . $process->getErrorOutput()
            );
            return new TestResult(TestStatus::runtimeError(), $process->getErrorOutput());
        }
        Log::debug("Helper: file " . $this->file->originalNameWithExtension() . " executed. Returned: " . $process->getOutput());

        if (!$this->file->ready_for_test || $test === null){
            return new TestResult(TestStatus::awaitingConfirmation(), $process->getOutput());
        }


        $testPassed = $this->checkTestResult($test, $process->getOutput());
        Log::debug("Test " . $test->id . ". Desired: " . $test->desired_result . ". Got: " . $process->getOutput() . ". Result: " . ($testPassed ? "passed" : "failed") . ".");

        if (!$testPassed)
        {
            return new TestResult(TestStatus::wrongAnswer(), $this->task->test_message = $test->error_message ?? $process->getOutput());
        }

        $timeLimitTestResult = $this->checkTimeLimit($test);
        if (!$timeLimitTestResult['passed']){
            $timeLimitExceededMessage = "Код выполнялся слишком долго: "
                . $timeLimitTestResult['time'] . " мс. Целевое значение: " . $test->time_limit . " мс.";
            $testErrorMessage = "Ошибка теста временного ограничения.";
            $testOverdue = $timeLimitTestResult['passed'] === false;
            $message = $testOverdue ? $timeLimitExceededMessage : $testErrorMessage;
            return new TestResult(TestStatus::runtimeExceeded(), $message);
        }

        return new TestResult(TestStatus::successStatus(), 'Тесты пройдены успешно.');

    }

    protected function checkTestResult(Test $test, string $output) : bool {
        $output = trim($output);
        if (!is_numeric($test->desired_result)){
            return trim($test->desired_result) == trim($output);
        }
        if (!is_numeric($output)){
            return false;
        }
        return abs((float)$test->desired_result - (float)$output) < $test->max_divergence;

    }

    protected function checkTimeLimit(Test $test) : array
    {
        if (!$test->hasTimeLimit()){
            return [
                "passed" => null,
                "time" => 0
            ];
        }
        $executionCommand = $this->getExecutionCommand() . " " . $test->input;
        $timeProcess = $this->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            "ts=\$(date +%s%N) ; $executionCommand ; tt=\$(((\$(date +%s%N) - \$ts)/1000000)); echo \$tt"
        ]);
        if (!$timeProcess->isSuccessful()){
            Log::warning("SshHelper: failed to execute time measure command. Output: " . $timeProcess->getErrorOutput());
            return [
                "passed" => null,
                "time" => 0
            ];
        }
        $executionTime = $timeProcess->getOutput();
        if (!is_numeric($executionTime)){
            Log::warning("SshHelper: execution time is not numeric");

            return [
                "passed" => null,
                "time" => 0
            ];
        }
        $executionTime = (int) $executionTime;
        return [
            "passed" => $test->time_limit <= $executionTime,
            "time" => $executionTime
        ];
    }
}
