<?php

namespace App\Jobs;

use App\Services\AdminAssessmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateMassAssessmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $requestData;

    protected $userId;

    public function __construct(array $requestData, $userId)
    {
        $this->requestData = $requestData;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(AdminAssessmentService $adminAssessmentService): void
    {
        $adminAssessmentService->createAssessment($this->requestData, $this->userId);
    }
}
