<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\LessonPlans\UpdateOrCreateRequest;
use App\Models\AINote;
use App\Models\AssessmentType;
use App\Models\BatchSession;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\SchoolYear;
use App\Models\User;
use App\Services\OpenAIService;
use App\Services\QuestionsService;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonPlanController extends Controller
{
    public function index(Request $request, OpenAIService $openAIService, TeacherService $teacherService, QuestionsService $questionsService): Response|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'batch_subject_id' => 'nullable|exists:batch_subjects,id',
            'month' => 'nullable|date_format:"Y-m"',
            'teacher_id' => 'nullable|exists:teachers,id',
            'quarter_id' => 'nullable|exists:quarters,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'school_year_id' => 'nullable|exists:school_years,id',
        ]);

        $teacherId = auth()->user()->isTeacher() ? auth()->user()->teacher->id : $request->input('teacher_id');

        if (! $teacherId) {
            abort(403);
        }

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/LessonPlans/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        $lessonPlanData = $teacherService->getLessonPlansData($request, $teacherId);

        // TODO: Change level category id
        $assessmentTypes = AssessmentType::where([
            ['school_year_id', SchoolYear::getActiveSchoolYear()->id], [
                'level_category_id', 1,
            ]])->get(['name', 'id']);

        return Inertia::render($page, array_merge($lessonPlanData, [
            'assessment_types' => $assessmentTypes,
            'openai_daily_usage' => auth()->user()->openai_daily_usage,
            'openai_daily_usage_limit' => env('DAILY_OPEN_AI_USER_USAGE_LIMIT'),
        ]));
    }

    public function updateOrCreate(UpdateOrCreateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();

        // Find the batch session with the given ID
        $batchSession = BatchSession::find($validatedData['batch_session_id']);

        if ($batchSession->lessonPlan) {
            // If a lesson plan is already associated with the batch session, update it
            $batchSession->lessonPlan->update($validatedData);
        } else {
            // If not, create a new lesson plan and associate it with the batch session
            $lessonPlan = LessonPlan::create($validatedData);
            $batchSession->lesson_plan_id = $lessonPlan->id;
            $batchSession->save();
        }

        // Return a response or redirect to another page
        return redirect()->back()->with('success', 'Lesson plan updated successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        LessonPlan::find($id)->delete();

        return redirect()->back()->with('success', 'Lesson plan deleted successfully');
    }

    public function generateNoteSuggestions(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $prompt = $request->input('prompt');
        $batchSubject = BatchSubject::find($request->input('batch_subject_id'));
        $grade = $batchSubject->load('batch.level')->batch->level->name;
        $subject = $batchSubject->load('subject')->subject->full_name;

        $explanationPrompt = "For grade '{$grade} {$subject}' students, explain '{$prompt}', in 3-5 paragraphs, focus on key concepts and objectives, ensuring alignment with the grade-level standards";

        return $openAIService->createCompletionStream($explanationPrompt);
    }

    public function saveNoteSuggestion(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'required|string',
            'lesson_plan_id' => 'nullable|exists:lesson_plans,id',
            'batch_session_id' => 'nullable|exists:batch_sessions,id',
        ]);

        AINote::create([
            'model' => 'gpt-4',
            'user_id' => auth()->user()->id,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'lesson_plan_id' => $request->input('lesson_plan_id'),
            'batch_session_id' => $request->input('batch_session_id'),
        ]);

        return redirect()->back()->with('success', 'Note saved successfully');
    }
}
