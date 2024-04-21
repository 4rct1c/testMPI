<?php

namespace App\Jobs;

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
use Spatie\Ssh\Ssh;

class SendAndCheckFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?TaskFile $file;
    /**
     * Create a new job instance.
     */
    public function __construct(protected Cluster $cluster, protected Task $task)
    {
        $this->onQueue('send_files');
        $this->file = $this->task->file;
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



        $uploadProcess = $this->createSshCommand()->upload($filePath, $this->cluster->files_directory);
        if (!$uploadProcess->isSuccessful()){
            Log::error('Failed to upload file with id ' . $this->file->id);
            return;
        }

        $compileProcess = $this->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $filename
        ]);

        if (strlen($compileProcess->getOutput())){
            $this->handleCompileError();
            return;
        }

        $executeProcess = $this->createSshCommand()->execute([
            'cd ' . $this->cluster->files_directory,
            $filename
        ]);

    }

    protected function createSshCommand() : Ssh {
        return Ssh::create($this->cluster->username, $this->cluster->host, $this->cluster->port)
            ->usePrivateKey('/var/www/.ssh/' .$this->cluster->key_name);
    }

    protected function handleCompileError() : void
    {

    }
}
