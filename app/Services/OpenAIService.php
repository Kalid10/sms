<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\StreamResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpenAIService
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
        unset($this->user->teacher);
    }

    public function createCompletion($prompt): array
    {
        $questionResponse = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 500,
            'temperature' => 0.5,
        ]);

        $responseText = $questionResponse['choices'][0]['text'];

        return explode("\n", trim($responseText));
    }

    public function createCompletionStream($prompt): StreamedResponse|JsonResponse
    {
        if (! $this->checkUsageLimit()) {
            return response()->json(['error' => 'You have exceeded your daily usage limit.'], 429);
        }

        $remainingUsage = $this->getRemainingDailyUsage();

        $maxTokens = min(200, $remainingUsage);

        $stream = OpenAI::completions()->createStreamed([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => $maxTokens,
        ]);

        return $this->getStreamedResponse($stream);
    }

    public function createChatStream(array $messages): StreamedResponse|JsonResponse
    {
        if (! $this->checkUsageLimit()) {
            return response()->json(['error' => 'You have exceeded your daily usage limit.'], 429);
        }

        $remainingUsage = $this->getRemainingDailyUsage();

        $maxTokens = min(200, $remainingUsage);

        $stream = OpenAI::chat()->createStreamed([
            'model' => 'gpt-4',
            'messages' => $messages,
            'max_tokens' => $maxTokens,
        ]);

        return $this->getStreamedResponse($stream, 'chat');
    }

    private function getStreamedResponse(StreamResponse $stream, $type = 'completion'): StreamedResponse
    {
        return response()->stream(function () use ($stream, $type) {
            $tokensUsed = $this->user->openai_daily_usage;

            foreach ($stream as $response) {
                if ($type === 'completion') {
                    $text = $response->choices[0]->text;
                } elseif ($type === 'chat' && isset($response->choices[0]->delta->content)) {
                    $text = $response->choices[0]->delta->content;
                } else {
                    continue;
                }

                // Update token usage
                $tokensUsed += $this->estimateTokenUsage($text);
                $this->updateUsageLimit($tokensUsed);

                if (connection_aborted()) {
                    break;
                }

                echo "event: update\n";
                echo 'data: '.$text;
                echo "\n\n";
                ob_flush();
                flush();
            }
            echo "event: usage_update\n";
            echo 'data: '.$this->user->openai_daily_usage;
            echo "\n\n";

            echo "event: update\n";
            echo 'data: <END_STREAMING_SSE>';
            echo "\n\n";

            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    public function checkUsageLimit(): bool
    {
        $dailyLimit = env('DAILY_OPEN_AI_USER_USAGE_LIMIT', 100);

        if ($this->user->openai_daily_usage >= $dailyLimit) {
            return false;
        }

        return true;
    }

    public function updateUsageLimit($tokenUsage): void
    {
        $this->user->openai_daily_usage = $tokenUsage;
        $this->user->save();
    }

    // Todo: Test if this is near accurate and find a better way to estimate token usage
    public function estimateTokenUsage($text): float
    {
        // Get the number of bytes in the UTF-8 encoded text
        $byteCount = strlen(utf8_encode($text));

        // Approximate the token count
        return ceil($byteCount / 4);
    }

    public function getRemainingDailyUsage(): int
    {
        // Assuming you have a daily limit set somewhere.
        $dailyLimit = env('DAILY_OPEN_AI_USER_USAGE_LIMIT', 100);

        // Calculate remaining usage
        return $dailyLimit - $this->user->openai_daily_usage;
    }
}
