<?php

namespace App\Http\Controllers\Web;

use App\Models\AssessmentType;
use App\Models\SchoolYear;
use App\Services\OpenAIService;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CopilotController extends Controller
{
    public function show(Request $request, TeacherService $teacherService): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|exists:batch_subjects,id',
            'month' => 'nullable|date_format:"Y-m"',
        ]);

        $teacherId = auth()->user()->teacher->id;

        $lessonPlansData = $teacherService->getLessonPlansData($request, $teacherId);

        // TODO: Change level category id
        $assessmentTypes = AssessmentType::where([
            ['school_year_id', SchoolYear::getActiveSchoolYear()->id], [
                'level_category_id', 1,
            ]])->get(['name', 'id']);

        return Inertia::render('Teacher/Copilot/Index', [
            'assessment_types' => $assessmentTypes,
            'lesson_plans_data' => $lessonPlansData,
        ]);
    }

    public function chat(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $messages = json_decode($request->input('messages'), true);

        return $openAIService->createChatStream($messages, 1000);
    }
}
