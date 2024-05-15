<?php

namespace App\Jobs;

use App\Helpers\TestHelper;
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

    protected TestHelper $helper;
    /**
     * Create a new job instance.
     */
    public function __construct(protected Cluster $cluster, protected Task $task)
    {
        $this->onQueue('send_files');
        $this->file = $this->task->file;
        $this->helper = new TestHelper($this->cluster, $this->task);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->file === null){
            return;
        }
        $filename = $this->file->generated_name . "." . $this->file->extension;

        $filePath = Storage::path(TaskFile::DIRECTORY . $filename);



        $uploadProcess = $this->helper->createSshCommand()->upload($filePath, $this->cluster->files_directory);
        if (!$uploadProcess->isSuccessful()){
            Log::warning('Failed to upload file with id ' . $this->file->id);
            return;
        }

        $compileProcess = $this->helper->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $this->helper->getCompileCommand()
        ]);

        if (!$compileProcess->isSuccessful()){
            Log::warning('Failed to compile file with id ' . $this->file->id);
            return;
        }

        Log::debug("Compiled! Received: " . $compileProcess->getOutput());

        if (strlen($compileProcess->getOutput())){
            $this->helper->handleCompilationError($compileProcess->getOutput());
            return;
        }

        $this->helper->runTests();

    }


}
