<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\AssignHomeroomRequest;
use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(Request $request): Response
    {
        $searchKey = $request->input('search');

        $teachers = Teacher::with([
            'user:id,name,email,phone_number,gender',
            'batchSubjects:id,subject_id,batch_id,teacher_id',
            'batchSubjects.subject:id,full_name',
            'batchSubjects.batch:id,section,level_id',
            'batchSubjects.batch.level:id,name',
            'homeroom:id,batch_id,teacher_id',
            'homeroom.batch:id,section,level_id',
            'homeroom.batch.level:id,name',
        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })
            ->paginate(15);

        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers,
        ]);
    }

    public function show(int $id = null): Response
    {
        if ($id === null && auth()->user()->isTeacher()) {
            $id = auth()->user()->teacher->id;
        }

        // Get teacher batches from batch_subjects table, make sure it is from active school year and distinct
        $batches = Batch::with([
            'level:id,name,level_category_id',
            'level.levelCategory:id,name',
            'level.levelCategory.assessmentTypes',
        ])->whereHas('subjects', function ($query) use ($id) {
            $query->where('teacher_id', $id);
        })->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->distinct()->get(['id', 'section', 'level_id'])
            ->map(function ($batch) {
                $batch->full_name = $batch->level->name.' - '.$batch->section;

                return $batch;
            });

        $teacher = Teacher::with([
            'user:id,name,email,phone_number,gender',
            'homeroom:id,batch_id,teacher_id',
            'homeroom.batch:id,section,level_id',
            'homeroom.batch.level:id,name',
            'batchSchedules',
            'batchSchedules.schoolPeriod:id,name,start_time,duration',
            'batchSchedules.batchSubject:id,subject_id,batch_id',
            'batchSchedules.batchSubject.subject:id,full_name',
            'batchSchedules.batchSubject.batch:id,section,level_id',
            'batchSchedules.batchSubject.batch.level:id,name',
            'batchSubjects' => function ($query) {
                $query->whereHas('batch', function ($batchQuery) {
                    $batchQuery->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                });
            },
            'batchSubjects.subject:id,full_name',
            'batchSubjects.batch:id,section,level_id,school_year_id',
            'batchSubjects.batch.level:id,name,level_category_id',
            'batchSubjects.batch.level.levelCategory:id,name',
            'feedbacks',
            'feedbacks.author:id,name',
        ])->select('id', 'user_id')->findOrFail($id);

        return Inertia::render('Teachers/Single', [
            'teacher' => $teacher,
            'batches' => $batches,
            'assessment_type' => $batches->unique()->pluck('level.levelCategory.assessmentTypes')->unique()->flatten(),
        ]);
    }

    public function assignHomeroomTeacher(AssignHomeroomRequest $request): RedirectResponse
    {
        // Get the teacher ID and batch ID from the request
        $teacherId = $request->teacher_id;
        $batchId = $request->batch_id;

        // If replace is true, delete the homeroom teacher
        if ($request->replace) {
            // Check if the teacher is assigned to only one batch
            if (HomeroomTeacher::where('teacher_id', $teacherId)->count() === 1) {
                HomeroomTeacher::where('teacher_id', $teacherId)->delete();
            } else {
                return redirect()->back()->withErrors(['teacher_id' => 'Cannot replace homeroom teacher. The teacher is assigned to more than one batch.']);
            }
        }

        Batch::findOrFail($batchId)->homeroomTeacher()->updateOrCreate(
            ['batch_id' => $batchId],
            ['teacher_id' => $teacherId]
        );

        return redirect()->back()->with('success', 'Homeroom teacher assigned successfully.');
    }

    public function removeHomeroomTeacher($id): RedirectResponse
    {
        // Find the homeroom teacher by ID or fail
        $homeroomTeacher = HomeroomTeacher::findOrFail($id);

        // Delete the homeroom teacher
        $homeroomTeacher->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Homeroom teacher removed successfully.');
    }

    public function getHomeroomTeachers(Request $request): Response
    {
        // Validate the request
        $request->validate([
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
        ]);

        // Get the teacher ID and batch ID from the request
        $teacherId = $request->teacher_id;
        $batchId = $request->batch_id;

        // Initialize the query builder for HomeroomTeacher
        $query = HomeroomTeacher::query();

        // If teacher ID is provided, filter by teacher ID
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        // If batch ID is provided, filter by batch ID
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        // Load the related Teacher and Batch models, and paginate the results if no filters are applied
        $homerooms = $query->with(['teacher.user', 'batch.level'])->when(! $teacherId && ! $batchId, function ($query) {
            return $query->paginate(10);
        }, function ($query) {
            return $query->get();
        });

        // Return the results with the view
        return Inertia::render('Welcome', [
            'homerooms' => $homerooms,
        ]);
    }
}
