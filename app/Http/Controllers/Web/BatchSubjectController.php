<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Batches\AssignSubjectsRequest;
use App\Http\Requests\Batches\AssignSubjectTeacherRequest;
use App\Http\Requests\BatchSubjects\AssignTeacherToBatchSubjectRequest;
use App\Http\Requests\BatchSubjects\SetBatchSubjectWeeklyFrequencyRequest;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BatchSubjectController extends Controller
{
    public function index(Request $request, Batch $batch, Subject $subject): Response
    {
        $searchKey = $request->input('teacher');

        $batchSubject = BatchSubject::where('batch_id', $batch->id)
            ->where('subject_id', $subject->id)
            ->first()->load('teacher.user');  // Get the batch subject

        $teachers = User::where('type', User::TYPE_TEACHER)
            ->when($searchKey, function ($query, $searchKey) {
                $query->where('name', 'like', '%'.$searchKey.'%');
            })->with('teacher')->get()->take(5);

        return Inertia::render('Admin/BatchSubjects/Index', [
            'teachers' => Inertia::lazy(fn () => $teachers),
            'batch' => $batch->load('level'),
            'subject' => $subject,
            'batchSubject' => $batchSubject,
            'batches' => $batchSubject->load('subject.activeBatches')
                ->subject->activeBatches
                ->where('level_id', $batch->level_id)->values(),
        ]);
    }

    public function assign(AssignSubjectsRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        foreach ($request->batches_subjects as $batchData) {
            $batchId = $batchData['batch_id'];

            // Getting the existing subject IDs for this batch
            $existingSubjectIds = BatchSubject::where('batch_id', $batchId)->pluck('subject_id')->toArray();

            // New subject IDs from the request
            $newSubjectIds = $batchData['subject_ids'];

            // Subject IDs to delete are those that are in the existing subject IDs but not in the new subject IDs
            $subjectIdsToDelete = array_diff($existingSubjectIds, $newSubjectIds);

            // Subject IDs to add are those that are in the new subject IDs but not in the existing subject IDs
            $subjectIdsToAdd = array_diff($newSubjectIds, $existingSubjectIds);

            // Deleting the unselected subjects
            if (! empty($subjectIdsToDelete)) {
                BatchSubject::where('batch_id', $batchId)
                    ->whereIn('subject_id', $subjectIdsToDelete)
                    ->delete();
            }

            // Inserting the newly selected subjects
            foreach ($subjectIdsToAdd as $subjectId) {
                BatchSubject::updateOrInsert(
                    ['batch_id' => $batchId, 'subject_id' => $subjectId],
                );
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Batch subject updated successfully.');
    }

    public function assignTeacher(AssignSubjectTeacherRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        // Loop through the batchesSubjects
        foreach ($request->batch_subjects_teachers as $batchSubjectTeacher) {
            $batchSubject = BatchSubject::find($batchSubjectTeacher['batch_subject_id']);
            $batchSubject->teacher_id = $batchSubjectTeacher['teacher_id'];
            $batchSubject->save();
        }

        DB::commit();

        return redirect()->back()->with('success', 'Batch subject added successfully.');
    }

    public function setWeeklyFrequency(SetBatchSubjectWeeklyFrequencyRequest $request): RedirectResponse
    {
        $batchSubject = BatchSubject::find($request->input('batch_subject'));

        if ($request->input('all_sections')) {
            $batchSubjects = BatchSubject::where('subject_id', $batchSubject->subject_id)->whereIn(
                'batch_id', $batchSubject->load('subject.activeBatches')
                    ->subject->activeBatches
                    ->where('level_id', $batchSubject->load('batch')->batch->level_id)->pluck('id')
            );

            $batchSubjects->each(function ($batchSubject) use ($request) {
                $batchSubject->weekly_frequency = $request->input('frequency');
                $batchSubject->save();
            });

            return redirect()->back()->with('success', 'Weekly frequency updated successfully.');
        }

        $batchSubject->weekly_frequency = $request->input('frequency');
        $batchSubject->save();

        return redirect()->back()->with('success', 'Weekly frequency updated successfully.');
    }

    public function assignTeacherToBatchSubject(AssignTeacherToBatchSubjectRequest $request, BatchSubject $batchSubject): RedirectResponse
    {
        $teacher = Teacher::find($request->input('teacher_id'));

        $batchSubject->teacher()->associate($teacher);
        $batchSubject->save();

        return redirect()->back()->with('success', 'Teacher assigned to class successfully.');
    }
}
