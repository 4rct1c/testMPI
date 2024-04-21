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

class FilesSendDispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


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
            return;
        }
        $this->dispatchSendJobs();
        self::dispatch($this->cluster->id)->delay(Carbon::now()->addMinutes($this->cluster->frequency_minutes));
    }


    protected function dispatchSendJobs() : void
    {
        $dispatchedCounter = 0;
        $dispatchedCounter = $this->iterateTasks(Exercise::byDeadline(), $dispatchedCounter);
        $this->iterateTasks(Exercise::byDeadline(true), $dispatchedCounter);
    }

    protected function iterateTasks(Collection $exercises, int $dispatchedCounter) : int
    {
        /** @var Exercise $exercise */
        foreach ($exercises as $exercise){
            foreach ($exercise->awaitingTasks() as $task){
                if ($dispatchedCounter >= $this->cluster->batch_size){
                    return $dispatchedCounter;
                }
                SendAndCheckFileJob::dispatch($task);
                $dispatchedCounter++;
            }
        }
        return $dispatchedCounter;
    }
}
