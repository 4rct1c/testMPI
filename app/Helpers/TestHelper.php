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
            ->usePrivateKey('/var/www/.ssh/' .$this->cluster->key_name);
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
            $executeProcess = $this->createSshCommand()->execute([
                'cd ' . $this->cluster->files_directory,
                $this->file->generated_name . "." . $this->file->extension,
                $test->input
            ]);
            $this->handleExecuteResponse($executeProcess, $test);
        }
    }

    public static function executeResponseIsError() : bool
    {
        return false;
    }

    public static function getCompileCommand(string $filename) : string
    {
        return $filename;
    }

    protected function handleExecuteResponse(Process $process, Test $test) : ?bool
    {
        if (!$process->isSuccessful()){
            Log::error('Failed to execute file with id ' . $this->file->id);
            return null;
        }
        if (static::executeResponseIsError()){
            $this->task->test_status_id = TestStatus::runtimeError()->id;
            $this->task->test_message = $process->getOutput();
            $this->task->save();
            return false;
        }
        return $this->checkTestResult($test, $process->getOutput());
    }

    protected function checkTestResult(Test $test, string $output) : bool {
        return trim($test->desired_output) == trim($output);
    }
}
