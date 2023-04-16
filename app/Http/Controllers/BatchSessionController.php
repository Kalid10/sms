<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BatchSessionController extends Controller
{
    public function batchSessions(Request $request): Response
    {
        $data = $request->validate([
            'batch_id' => 'integer|exists:batches,id',
            'date' => 'date',
            'batch_schedule_id' => 'integer|exists:batch_schedules,id',
            'teacher_id' => 'integer|exists:teachers,id',
            'status' => 'string|in:scheduled,in_progress,completed',
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
                },
                'sessions.batchSchedule.batchSubject.subject',
                'sessions.batchSchedule.schoolPeriod',
            ])
            ->first()->sessions;

        return Inertia::render('Welcome', [
            'sessions' => $batchSessions,
        ]);
    }
}
