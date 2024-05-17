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

class TestHelper
{

    protected TaskFile $file;

    public function __construct(protected Cluster $cluster, protected Task $task)
    {
        $file = $this->task->file;
        if ($file !== null) {
            $this->file = $file;
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
            $this->executeFile($test);
        }
    }

    public function executeFile(?Test $test = null) : void
    {
        $executeCommand = $this->getExecutionCommand();
        if ($test !== null){
            $executeCommand .= " " . $test->input;
        }
        $executeProcess = $this->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $executeCommand
        ]);
        $this->handleExecuteResponse($executeProcess, $test);
    }

    public function getCompileCommand() : string
    {
        return "mpiCC " . $this->file->generatedNameWithExtension() . " -o " . $this->file->generated_name;
    }

    public function getExecutionCommand() : string
    {
        return "mpirun -np " . $this->cluster->processors_count . " ./" . $this->file->generated_name;
    }

    protected function handleExecuteResponse(Process $process, ?Test $test = null) : bool
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
            return false;
        }
        Log::debug("Helper: file " . $this->file->originalNameWithExtension() . " executed. Returned: " . $process->getOutput());

        if ($this->file->ready_for_test){
            $testPassed = $this->checkTestResult($test, $process->getOutput());
            $this->task->test_status_id = $testPassed ? TestStatus::successStatus()->id : TestStatus::wrongAnswer()->id;
            $this->task->test_message = $process->getOutput();
            $this->task->save();
            return $testPassed;
        }

        $this->task->test_status_id = TestStatus::awaitingConfirmation()->id;
        $this->task->test_message = $process->getOutput();
        $this->task->save();

        return false;
    }

    protected function checkTestResult(Test $test, string $output) : bool {
        return trim($test->desired_output) == trim($output);
    }
}
