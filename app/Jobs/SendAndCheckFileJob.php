<?php

namespace App\Jobs;

use App\Helpers\SshHelper;
use App\Models\Cluster;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendAndCheckFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?TaskFile $file;

    protected SshHelper $helper;
    /**
     * Create a new job instance.
     */
    public function __construct(protected Cluster $cluster, protected Task $task)
    {
        $this->onQueue('send_files');
        $this->file = $this->task->file;
        $this->helper = new SshHelper($this->cluster, $this->task);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->file === null){
            Log::warning('SendJob: file is null!');
            return;
        }
        Log::debug("SendJob: handling file " . $this->file->originalNameWithExtension() . " (id = " . $this->file->id . ")");

        $filePath = Storage::path(TaskFile::DIRECTORY . $this->file->generatedNameWithExtension());

        $uploadProcess = $this->helper->createSshCommand()->upload($filePath, $this->cluster->files_directory);
        if (!$uploadProcess->isSuccessful()){
            Log::warning('SendJob: failed to upload file with id ' . $this->file->id .
                "\nStatus: " . $uploadProcess->getStatus() .
                "\nOutput: " . $uploadProcess->getOutput() .
                "\nError output: " . $uploadProcess->getErrorOutput()
            );
            return;
        }

        $compileProcess = $this->helper->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $this->helper->getCompileCommand()
        ]);

        if (!$compileProcess->isSuccessful()){
            Log::debug('SendJob: failed to compile file with id ' . $this->file->id .
                "\nStatus: " . $compileProcess->getStatus() .
                "\nError output: " . $compileProcess->getErrorOutput()
            );
            $this->helper->handleCompilationError($compileProcess->getErrorOutput());
            return;
        }

        Log::debug("SendJob: file " . $this->file->originalNameWithExtension() .
            " (id " . $this->file->id . ") compiled. Received: " . $compileProcess->getOutput());

        $this->file->ready_for_test ? $this->helper->runTests() : $this->helper->executeFile();

    }


}
