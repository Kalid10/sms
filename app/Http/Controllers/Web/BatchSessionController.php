<?php

namespace App\Http\Controllers\Web;

use App\Models\Batch;
use App\Models\BatchSession;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BatchSessionController extends Controller
{
    public function batchSessions(Request $request): Response
    {
        $data = $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'date' => 'date',
            'batch_schedule_id' => 'integer|exists:batch_schedules,id',
            'teacher_id' => 'integer|exists:teachers,id',
            'status' => 'string|in:scheduled,in_progress,completed',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable|after_or_equal:start_date',
        ]);

        $batchSessions = Batch::where('id', $data['batch_id'])
            ->with([
                'sessions' => function ($query) use ($data) {
                    if (isset($data['date'])) {
                        $query->where('date', $data['date']);
                    }

                    if (isset($data['status'])) {
                        $query->where('status', $data['status']);
                    }

                    if (isset($data['teacher_id'])) {
                        $query->where('teacher_id', $data['teacher_id']);
                    }

                    if (isset($data['start_date']) && isset($data['end_date'])) {
                        $query->whereBetween('date', [$data['start_date'], $data['end_date']]);
                    }
                },
                'sessions.batchSchedule.batchSubject.subject',
                'sessions.batchSchedule.schoolPeriod',
            ])
            ->first()->sessions;

        return Inertia::render('Welcome', [
            'sessions' => $batchSessions,
        ]);
    }

    public function teacherSessions(Request $request): Response
    {
        $data = $request->validate([
            'batch_id' => 'integer|exists:batches,id',
            'date' => 'date',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'status' => 'string|in:scheduled,in_progress,completed',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable|after_or_equal:start_date',
        ]);

        $teacherSessions = BatchSession::query()
            ->where('teacher_id', $data['teacher_id'])
            ->when(isset($data['status']), function ($query) use ($data) {
                return $query->where('status', $data['status']);
            }, function ($query) {
                return $query->where('status', BatchSession::STATUS_SCHEDULED);
            })
            ->when(isset($data['date']), function ($query) use ($data) {
                return $query->where('date', $data['date']);
            })
            ->when(isset($data['start_date']) && isset($data['end_date']), function ($query) use ($data) {
                return $query->whereBetween('date', [$data['start_date'], $data['end_date']]);
            })
            ->with([
                'batchSchedule' => function ($query) use ($data) {
                    if (isset($data['batch_id'])) {
                        $query->where('batch_id', $data['batch_id']);
                    }
                },
                'batchSchedule.batchSubject.subject',
                'batchSchedule.schoolPeriod',
            ])
            ->get();

        return Inertia::render('Welcome', [
            'teacher_sessions' => $teacherSessions,
        ]);
    }

    public function activeSession(Batch $batch): Response
    {
        $activeSessions = BatchSession::where('status', BatchSession::STATUS_IN_PROGRESS)->whereHas('batchSchedule',
            function ($query) use ($batch) {
                $query->where('batch_id', $batch->id);
            })
            ->with([
                'batchSchedule.batchSubject.subject:id,category,full_name,short_name,priority,tags',
                'batchSchedule.schoolPeriod:id,duration,level_category_id,name,school_year_id',
                'batchSchedule.batch',
                'teacher.user:id,name,email,gender,date_of_birth,type',
            ])->get();

        return Inertia::render('Welcome', [
            'active_sessions' => $activeSessions,
        ]);
    }
}
