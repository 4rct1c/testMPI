<?php

namespace App\Jobs;

use App\Helpers\SshHelper;
use App\Models\Cluster;
use App\Models\TaskFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected SshHelper $helper;
    /**
     * Create a new job instance.
     */
    public function __construct(protected Cluster $cluster, protected TaskFile $file)
    {
        $this->onQueue('delete_files');
        $this->helper = new SshHelper($this->cluster, $this->file);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $removeFileCommand = 'rm ' . $this->cluster->files_directory . '/' . $this->file->generatedNameWithExtension();
        $removeFileProcess = $this->helper->createSshCommand()->execute($removeFileCommand);
        if ($removeFileProcess->isSuccessful()){
            Log::debug("DeleteFileJob: file " . $this->file->generatedNameWithExtension() . " was deleted.");
        } else {
            Log::warning("DeleteFileJob: file wasn't deleted. Error: " . $removeFileProcess->getErrorOutput());
        }

        $removeCompiledFileCommand = 'rm ' . $this->cluster->files_directory . '/' . $this->file->generated_name;
        $removeCompiledFileProcess = $this->helper->createSshCommand()->execute($removeCompiledFileCommand);
        if ($removeCompiledFileProcess->isSuccessful()){
            Log::debug("DeleteFileJob: file " . $this->file->generated_name . " was deleted.");
        } else {
            Log::warning("DeleteFileJob: file wasn't deleted. Error: " . $removeCompiledFileProcess->getErrorOutput());
        }
    }
}
