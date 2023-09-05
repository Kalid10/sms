<?php

namespace App\Jobs;

use App\Services\StudentFeeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StudentFeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    protected Collection $fees;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, Collection $fees)
    {
        $this->data = $data;
        $this->fees = $fees;
    }

    /**
     * Execute the job.
     */
    public function handle(StudentFeeService $studentTuitionService): void
    {
        $studentTuitionService->createStudentFee($this->data, $this->fees);
    }
}
