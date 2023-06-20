<?php

namespace App\Http\Controllers\Web;

use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CopilotController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Teacher/Copilot');
    }

    public function search(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $searchText = $request->input('prompt');
        $explanationPrompt = 'Give me five questions for grade 5 students on the following topic: '.$searchText;

        return $openAIService->generateCompletionStream($searchText, 1000);
    }
}
