<?php

namespace App\Jobs;

use App\Services\OpenAIService;
use App\Services\QuestionsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QuestionGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;

    private array $requestData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $requestData, $userId)
    {
        $this->requestData = $requestData;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(QuestionsService $questionsService, OpenAIService $openAIService): void
    {
        $questionsService->generateQuestions($this->requestData, $openAIService, $this->userId);
    }
}
