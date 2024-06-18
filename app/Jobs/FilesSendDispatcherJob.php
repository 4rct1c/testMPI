<?php

namespace App\Jobs;

use App\Models\Cluster;
use App\Models\Exercise;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\Ssh\Ssh;

class FilesSendDispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 1;

    protected ?Cluster $cluster;

    /**
     * Create a new job instance.
     */
    public function __construct(int $clusterId)
    {
        $this->onQueue('send_files');
        $this->cluster = Cluster::find($clusterId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->cluster === null) {
            Log::error('Cluster not found!');
            return;
        }
        self::dispatch($this->cluster->id)->delay(Carbon::now()->addMinutes($this->cluster->frequency_minutes));
        if ($this->isClusterAvailable()) {
            $this->dispatchSendJobs();
        } else {
            Log::error('DispatcherJob: cluster "' . $this->cluster->host
                . '" (id=' . $this->cluster->id . ') is not available!');
        }
    }

    protected function isClusterAvailable(): bool
    {
        try {
            return Ssh::create($this->cluster->username, $this->cluster->host, $this->cluster->port)
                ->setTimeout(30)
                ->usePrivateKey($this->cluster->getKeyPath())
                ->execute('ls')
                ->isSuccessful();
        }
        catch (\Exception $exception){
            Log::error("Failed to connect to cluster. Exception: " . $exception->getMessage());
            return false;
        }
    }


    protected function dispatchSendJobs(): void
    {
        $dispatchedCounter = 0;
        $dispatchedCounter = $this->iterateTasks(Exercise::byDeadline(), $dispatchedCounter);
        $this->iterateTasks(Exercise::withoutDeadline(), $dispatchedCounter);
        $this->iterateTasks(Exercise::byDeadline(true), $dispatchedCounter);
    }

    protected function iterateTasks(Collection $exercises, int $dispatchedCounter): int
    {
        /** @var Exercise $exercise */
        foreach ($exercises as $exercise) {
            foreach ($exercise->awaitingTasks() as $task) {
                if ($dispatchedCounter >= $this->cluster->batch_size) {
                    return $dispatchedCounter;
                }
                SendAndCheckFileJob::dispatch($this->cluster, $task);
                $dispatchedCounter++;
            }
        }
        return $dispatchedCounter;
    }
}
