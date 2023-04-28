<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonPlans\UpdateOrCreateRequest;
use App\Models\BatchSession;
use App\Models\LessonPlan;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LessonPlanController extends Controller
{
    public function index(): Response
    {
        $lessonPlan = BatchSession::whereHas('batchSubject', function ($query) {
            $query->where('teacher_id', auth()->user()->teacher->id);
        })->with(['lessonPlan' => function ($query) {
            $query->with('batchSessions');
        }])->first()->lessonPlan()->get();

        $activityLogs = Activity::all();

        // Get all BatchSessions
        $batchSessions = BatchSession::all();

        $availableBatchSessions = BatchSession::whereHas('batchSubject', function ($query) {
            $query->where('teacher_id', auth()->user()->teacher->id);
        })->where('status', BatchSession::STATUS_SCHEDULED)->get();

        return Inertia::render('LessonPlans/Index', [
            'lesson_plan' => $lessonPlan,
            'activity_logs' => $activityLogs,
            'batch_sessions' => $batchSessions,
            'availableBatchSessions' => $availableBatchSessions,
        ]);
    }

    public function updateOrCreate(UpdateOrCreateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();

        // Find a lesson plan that contains at least one of the batch_session_ids in the request
        $lessonPlan = LessonPlan::where(function ($query) use ($validatedData) {
            foreach ($validatedData['batch_session_ids'] as $batchSessionId) {
                $query->orWhereJsonContains('batch_session_ids', $batchSessionId);
            }
        })->first();

        if ($lessonPlan) {
            // If found, update the lesson plan with the new batch_session_ids
            $lessonPlan->update($validatedData);
        } else {
            Log::info($validatedData);
            // If not found, create a new lesson plan with the new batch_session_ids
            LessonPlan::create($validatedData);
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
