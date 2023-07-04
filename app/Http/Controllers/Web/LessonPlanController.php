<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\LessonPlans\UpdateOrCreateRequest;
use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\User;
use App\Services\OpenAIService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonPlanController extends Controller
{
    public function index(Request $request, OpenAIService $openAIService): Response|\Illuminate\Http\RedirectResponse
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
        $batchSubjectId = $request->input('batch_subject_id') ?? BatchSubject::where('teacher_id', $teacherId)
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })->first()?->id;
        $batchSubject = BatchSubject::with([
            'subject:id,full_name',
            'batch:id,section,level_id',
            'batch.level:id,name',
        ])->where([
            ['id', $batchSubjectId],
            ['teacher_id', $teacherId],
        ])->firstOrFail();

        if ($batchSubject->teacher_id !== $teacherId && ! auth()->user()->type === User::TYPE_ADMIN) {
            return redirect()->back()->with('error', 'You are not assigned to this subject.');
        }

        $batchId = $batchSubject->batch_id;
        $currentMonth = $request->input('month') ? Carbon::createFromFormat('Y-m', $request->input('month')) : Carbon::now();
        $firstDayOfMonth = $currentMonth->copy()->startOfMonth();
        $lastDayOfMonth = $currentMonth->copy()->endOfMonth();

        $teacherSubjects = BatchSubject::with([
            'subject:id,full_name',
            'batch:id,section,level_id',
            'batch.level:id,name,level_category_id',
        ])->where('teacher_id', $teacherId)
            ->whereHas('batch', fn ($query) => $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id))
            ->distinct()
            ->get(['id', 'subject_id', 'batch_id']);

        $quarterFilter = $request->input('quarter_id');

        $batchSessionsWithLessonPlans = BatchSession::where('teacher_id', $teacherId)
            ->whereHas('batchSchedule.batchSubject', fn ($query) => $query->where('batch_subject_id', $batchSubject->id))
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->when($quarterFilter, function (Builder $query) use ($quarterFilter) {
                $query->whereHas('batchSchedule', function (Builder $query) use ($quarterFilter) {
                    $query->where('quarter_id', $quarterFilter);
                });
            })
            ->with([
                'batchSchedule.schoolPeriod',
                'batchSchedule.batchSubject.subject',
                'lessonPlan',
            ])
            ->get()
            ->sortBy('date')
            ->groupBy(function ($batchSession) use ($firstDayOfMonth) {
                $date = Carbon::parse($batchSession->date);
                $weekNumber = $date->weekOfYear - $firstDayOfMonth->weekOfYear + 1;

                return 'week'.max($weekNumber, 1);
            });

        $weeksInMonth = (int) ($currentMonth->daysInMonth / 7) + ($currentMonth->daysInMonth % 7 != 0 ? 1 : 0);

        $weeklyBatchSessions = array_fill_keys(array_map(fn ($i) => "week{$i}", range(1, $weeksInMonth)), collect([]));

        foreach ($batchSessionsWithLessonPlans as $weekKey => $batchSessions) {
            $weeklyBatchSessions[$weekKey] = $batchSessions;
        }

        $months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => Carbon::today()->month($month)->format('Y-m'),
                'label' => Carbon::today()->month($month)->format('F'),
            ];
        });

        $prompt = $request->input('prompt') ?? null;
        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/LessonPlans/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        $quarters = Quarter::with('semester.schoolYear')->get();
        $semesters = Semester::with('schoolYear')->get();
        $schoolYears = SchoolYear::all();

        return Inertia::render($page, [
            'batch_sessions' => $weeklyBatchSessions,
            'batch' => Batch::find($batchId)->load('level'),
            'subjects' => $teacherSubjects,
            'weeks_in_month' => $weeksInMonth,
            'lesson_plan_subject' => $batchSubject,
            'months' => $months,
            'selected_month' => $currentMonth->format('Y-m'),
            'teacher' => Teacher::find($teacherId)->load('user'),
            'questions' => $prompt ? Inertia::lazy(fn () => $openAIService->lessonPlanHelper($prompt, $batchSubject)) : null,
            'filters' => [
                'quarter_id' => $quarterFilter ?? Quarter::getActiveQuarter()->id,
                'quarters' => $quarters,
                'semesters' => $semesters,
                'school_years' => $schoolYears,
                'semester_id' => $request->input('semester_id') ?? Semester::getActiveSemester()->id,
                'school_year_id' => $request->input('school_year_id') ?? SchoolYear::getActiveSchoolYear()->id,
            ],
        ]);
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

    public function noteSuggestions(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $prompt = $request->input('prompt');
        $batchSubject = BatchSubject::find($request->input('batch_subject_id'));
        $grade = 8;
        $subject = 'Biology';
        $explanationPrompt = "The lesson plan title is '{$prompt}' for a '{$grade}' level class in the subject of '{$subject}'. Provide a brief 2-5 paragraph explanation of '{$prompt}'.";

//        $explanationPrompt = "I want you to act as a lesson plan advisor.The subject is '{$subject}' for grade '{$grade}' provide a brief 2-5 paragraphs explaining this lesson plan title: '{$prompt}";
        return $openAIService->createCompletionStream($explanationPrompt);
    }
}
