<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

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
}
