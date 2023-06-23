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

    public function chat(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $messages = json_decode($request->input('messages'), true);

        return $openAIService->createChatStream($messages, 1000);
    }
}
