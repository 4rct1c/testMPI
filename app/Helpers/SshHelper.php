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
        /** @var Test $test */
        foreach ($this->task->exercise->tests as $test){
            $process = $this->executeFile($test);
            $this->handleExecuteResponse($process, $test);
        }
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

    protected function handleExecuteResponse(Process $process, ?Test $test = null) : TestStatus
    {
        if (!$process->isSuccessful()){
            Log::debug('Helper: execution error in file ' . $this->file->originalNameWithExtension() .
                ' (id ' . $this->file->id . ')' .
                "\nStatus: " . $process->getStatus() .
                "\nError output: " . $process->getErrorOutput()
            );
            $this->task->test_status_id = TestStatus::runtimeError()->id;
            $this->task->test_message = $process->getErrorOutput();
            $this->task->save();
            return TestStatus::runtimeError();
        }
        Log::debug("Helper: file " . $this->file->originalNameWithExtension() . " executed. Returned: " . $process->getOutput());

        if (!$this->file->ready_for_test || $test === null){
            $this->task->test_status_id = TestStatus::awaitingConfirmation()->id;
            $this->task->test_message = $process->getOutput();
            $this->task->save();
            return TestStatus::awaitingConfirmation();
        }


        $testPassed = $this->checkTestResult($test, $process->getOutput());
        if (!$testPassed)
        {
            $testStatus = TestStatus::wrongAnswer();
            $this->task->test_message = $test->error_message ?? $process->getOutput();

            $this->task->test_status_id = $testStatus->id;
            $this->task->save();
            return $testStatus;
        }

        $timeLimitTestResult = $this->checkTimeLimit($test);
        if (!$timeLimitTestResult['passed']){
            $testStatus = TestStatus::runtimeExceeded();
            $timeLimitExceededMessage = "Код выполнялся слишком долго: "
                . $timeLimitTestResult['time'] . " мс. Целевое значение: " . $test->time_limit . " мс.";
            $this->task->test_message = $timeLimitTestResult['passed'] === false ? $timeLimitExceededMessage : "Ошибка теста временного ограничения.";
        }
        else {
            $testStatus = TestStatus::successStatus();
            $this->task->test_message = 'Тесты пройдены успешно.';
        }

        $this->task->test_status_id = $testStatus->id;
        $this->task->save();

        return $testStatus;

    }

    protected function checkTestResult(Test $test, string $output) : bool {
        return trim($test->desired_result) == trim($output);
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
