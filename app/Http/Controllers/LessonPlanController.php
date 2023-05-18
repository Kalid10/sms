<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonPlans\UpdateOrCreateRequest;
use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LessonPlanController extends Controller
{
    public function index(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'batch_subject_id' => 'nullable|exists:batch_subjects,id',
            'month' => 'nullable|date_format:"Y-m"',
        ]);

        $teacherId = auth()->user()->teacher->id;

        $batchSubject = BatchSubject::with([
            'subject:id,full_name',
            'batch:id,section,level_id',
            'batch.level:id,name',
        ])->when($request->input('batch_subject_id'), function ($query, $batchSubjectId) {
            $query->where('id', $batchSubjectId);
        }, function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->firstOrFail();

        if ($batchSubject->teacher_id !== $teacherId) {
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

        $batchSessionsWithLessonPlans = BatchSession::where('teacher_id', $teacherId)
            ->whereHas('batchSchedule.batchSubject', fn ($query) => $query->where('batch_subject_id', $batchSubject->id))
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
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

        return Inertia::render('Teacher/LessonPlans/Index', [
            'batch_sessions' => $weeklyBatchSessions,
            'batch' => Batch::find($batchId)->load('level'),
            'subjects' => $teacherSubjects,
            'weeks_in_month' => $weeksInMonth,
            'lesson_plan_subject' => $batchSubject,
            'months' => $months,
            'selected_month' => $currentMonth->format('Y-m'),
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
}
