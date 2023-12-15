<?php

namespace App\Http\Controllers\API\Teachers;

use App\Helpers\AbsenteeHelper;
use App\Http\Requests\API\Teachers\BatchSessionRequest;
use App\Http\Resources\Teachers\BatchSessionCollection;
use App\Http\Resources\Teachers\BatchSessionResource;
use App\Http\Resources\Teachers\BatchStudentCollection;
use App\Http\Resources\Teachers\EmptyResource;
use App\Models\Absentee;
use App\Models\BatchSession;
use App\Models\BatchStudent;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BatchSessionController extends Controller
{
    /**
     * @return BatchSessionCollection|BatchSessionResource|EmptyResource
     *
     * Get all batch sessions of a teacher
     */
    public function index(BatchSessionRequest $request, ?BatchSession $batchSession): BatchSessionCollection|BatchSessionResource|EmptyResource
    {
        parent::teacher()->activeBatches()->each(function ($batch) use ($request) {
            $batch->getSessions($request->input('force', false));
        });

        if ($batchSession->exists) {
            return new BatchSessionResource($batchSession->load([
                'batchSchedule.schoolPeriod',
                'batchSchedule.batchSubject.batch.level',
                'batchSchedule.batchSubject.batch.schoolYear',
                'batchSchedule.batchSubject.subject',
            ]));
        }

        if ($request->has('status') && $request->input('status') === 'in_progress') {
            if (parent::teacher()->inProgressBatchSession) {
                return new BatchSessionResource(parent::teacher()->inProgressBatchSession->load([
                    'batchSchedule.schoolPeriod',
                    'batchSchedule.batchSubject.batch.level',
                    'batchSchedule.batchSubject.batch.schoolYear',
                    'batchSchedule.batchSubject.subject',
                ]));
            }

            return new EmptyResource([]);
        }

        if ($request->has('batch_subject_id')) {

            $batchSessions = BatchSession::where('batch_subject_id', $request->input('batch_subject_id'))
                ->when($request->has('status'), function ($query) {
                    return $query->where('status', $this->input('status'));
                })
                ->get()
                ->sortBy('date');

            return new BatchSessionCollection($batchSessions);
        }

        $batchSessions = parent::teacher()->load([
            'batchSessions.batchSchedule.schoolPeriod',
            'batchSessions.batchSchedule.batchSubject.batch.level',
            'batchSessions.batchSchedule.batchSubject.batch.schoolYear',
            'batchSessions.batchSchedule.batchSubject.subject',
        ])->batchSessions?->sortBy('date');

        return new BatchSessionCollection($batchSessions);
    }

    /**
     * @throws Throwable
     */
    public function absentees(Request $request): \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
    {
        $request->validate([
            'batch_session_id' => 'required|integer|exists:batch_sessions,id',
            'user_type' => 'required|in:'.User::TYPE_STUDENT,
            'absentees' => 'nullable|array|min:0',
            'absentees.*.user_id' => 'required|integer|exists:users,id|distinct:strict',
            'absentees.*.reason' => 'nullable|string',
        ]);

        // Get the batch session
        $batchSession = BatchSession::find($request->batch_session_id)->load('batchSchedule');

        // Check if the batch session is already closed
        if ($batchSession->status !== BatchSession::STATUS_IN_PROGRESS) {
            Log::info('Batch session is already closed.');

            return response([
                'message' => 'Batch session is already closed.',
            ], 422);
        }

        // Check if the student is enrolled in the batch
        $batchId = $batchSession->batchSchedule->batch_id;
        $absenteeUserIds = array_column($request->absentees, 'user_id');
        $absenteeUsers = User::with('student.batches')->whereIn('id', $absenteeUserIds)->get();

        foreach ($absenteeUsers as $absenteeUser) {
            Log::info('Absentee user '.$absenteeUser);
            if (! $absenteeUser->student->batches->pluck('batch_id')->contains($batchId)) {
                return response([
                    'message' => 'Student is not enrolled in the batch.',
                ], 422);
            }
        }

        // Check if the teacher is assigned to the batchSubject
        $batchSubject = $batchSession->load('batchSchedule.batchSubject.teacher.user')->batchSchedule->batchSubject;

        if (auth()->user()->id !== $batchSubject->teacher->user->id) {
            return response([
                'message' => 'You are not assigned to this class.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Make the absentees array collection
            $absentees = collect($request->absentees);

            // Update existing records with new reasons or insert new records
            foreach ($absentees as $absentee) {
                Absentee::updateOrInsert(
                    [
                        'batch_session_id' => $request->batch_session_id,
                        'user_id' => $absentee['user_id'],
                    ],
                    [
                        'reason' => $absentee['reason'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Fetch existing absent records for the specified batch
            $existingAbsentRecords = Absentee::where('batch_session_id', $request->batch_session_id)->pluck('user_id');

            // Find students to be removed from the absent list
            $usersToRemove = $existingAbsentRecords->diff($absentees->pluck('user_id'));

            // Remove students from the absent list
            Absentee::where('batch_session_id', $request->batch_session_id)->whereIn('user_id', $usersToRemove)->delete();

            foreach ($usersToRemove as $userId) {
                $absentee = Absentee::where('user_id', $userId)->whereDate('created_at', Carbon::today())->latest()->first();

                $absentee?->update([
                    'next_class_attended_flag' => true,
                ]);
            }

            DB::commit();

            AbsenteeHelper::computeAbsenteeData($absentees, $batchSession);

            // Map data and recalculate for the removed users
            $usersToRemove = $existingAbsentRecords->diff($absentees->pluck('user_id'))
                ->map(function ($userId) {
                    return ['user_id' => $userId];
                });

            AbsenteeHelper::computeAbsenteeData($usersToRemove, $batchSession);
        } catch (Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());

            return response([
                'message' => 'Something went wrong.',
            ], 500);
        }

        return response([
            'message' => 'Absentees updated successfully.',
        ]);

    }

    public function getAbsentees(BatchSessionRequest $request, BatchSession $batchSession): BatchStudentCollection
    {
        $batchId = $batchSession->load('batchSchedule')->batchSchedule->batch_id;
        $batchSessionAbsentees = $batchSession->load('absentees.user')?->absentees->pluck('user_id');

        $batchStudents = BatchStudent::with('student.user', 'batch')->where('batch_id', $batchId)
            ->get()->filter(function ($batchStudent) use ($batchSessionAbsentees) {
                return $batchSessionAbsentees->contains($batchStudent->student->user_id);
            });

        return new BatchStudentCollection($batchStudents);
    }
}
