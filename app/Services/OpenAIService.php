<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\StreamResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpenAIService
{
    public function lessonPlanHelper($prompt, $batchSubject): array
    {
        $grade = 8;
        $subject = 'Biology';

        // Prepare a more detailed prompt for the questions
        $questionPrompt = "Given the topic '{$prompt}' for a '{$grade}' level class in the subject of '{$subject}', generate a list of potential questions that students could be asked about '{$prompt}'.";
        $questionResponse = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $questionPrompt,
            'max_tokens' => 200,
            'temperature' => 0.2,
        ]);

        $responseText = $questionResponse['choices'][0]['text'];

        return explode("\n", trim($responseText));
    }

    public function createCompletionStream($prompt, $maxTokens = 400): StreamedResponse
    {
        $stream = OpenAI::completions()->createStreamed([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => $maxTokens,
        ]);

        return $this->getStreamedResponse($stream);
    }

    public function createChatStream(array $messages, $maxTokens = 400): StreamedResponse
    {
        $stream = OpenAI::chat()->createStreamed([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 500,
        ]);

        return $this->getStreamedResponse($stream, 'chat');
    }

    private function getStreamedResponse(StreamResponse $stream, $type = 'completion'): StreamedResponse
    {
        return response()->stream(function () use ($stream, $type) {
            foreach ($stream as $response) {
                if ($type === 'completion') {
                    $text = $response->choices[0]->text;
                } elseif ($type === 'chat' && isset($response->choices[0]->delta->content)) {
                    $text = $response->choices[0]->delta->content;
                } else {
                    continue;
                }

                if (connection_aborted()) {
                    break;
                }

                echo "event: update\n";
                echo 'data: '.$text;
                echo "\n\n";
                ob_flush();
                flush();
            }

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
}
