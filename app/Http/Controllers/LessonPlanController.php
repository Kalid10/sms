<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonPlans\UpdateOrCreateRequest;
use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\LessonPlan;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LessonPlanController extends Controller
{
    public function index(): Response
    {
        $batchId = 1;
        $currentMonth = Carbon::now();
        $firstDayOfMonth = $currentMonth->copy()->startOfMonth()->addMonth();
        $lastDayOfMonth = $currentMonth->copy()->endOfMonth()->addMonth();

        $batchSessionsWithLessonPlans = BatchSession::where('teacher_id', auth()->user()->teacher->id)
            ->whereHas('batchSchedule', fn ($query) => $query->where('batch_id', $batchId))
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->with([
                'batchSchedule.schoolPeriod',
                'batchSchedule.batchSubject.subject',
                'lessonPlan',
            ])
            ->get()
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

        return Inertia::render('LessonPlans/Index', [
            'batch_sessions' => $weeklyBatchSessions,
            'batch' => Batch::find($batchId)->load('level'),
            'weeks_in_month' => $weeksInMonth,
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
