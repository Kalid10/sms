<?php

namespace App\Services;

use App\Models\BatchScheduleConfig;
use App\Models\BatchSubject;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

enum BatchSubjectService
{
    public static function update(Request $request): RedirectResponse
    {
        $request->validate([
            'batch_subjects' => 'required|array',
            'batch_subjects.*.id' => 'required|exists:batch_subjects,id',
            'batch_subjects.*.teacher_id' => 'required|exists:teachers,id',
            'batch_subjects.*.weekly_frequency' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->input('batch_subjects') as $batchSubject) {

                // Get the teachers batch subjects for this school year and check if the teacher total weekly frequency is not exceeded from the batch_schedule_config table
                $teacherBatchSubjects = BatchSubject::where('teacher_id', $batchSubject['teacher_id'])->with(['batch' => function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                }])->get();

                if ($request->input('weekly_frequency')) {
                    $teacherTotalWeeklyFrequency = $teacherBatchSubjects->sum('weekly_frequency');

                    $batchScheduleConfig = BatchScheduleConfig::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->first();

                    if ($teacherTotalWeeklyFrequency + $batchSubject['weekly_frequency'] > $batchScheduleConfig ? $batchScheduleConfig?->max_periods_per_week : 40) {
                        DB::rollBack();

                        return redirect()->back()->withErrors(['error', $batchSubject['teacher']['user']['name'].' has exceeded the weekly frequency limit of '.$batchScheduleConfig?->max_periods_per_week.' periods per week.']);
                    }
                }
                $batchSubjectModel = BatchSubject::find($batchSubject['id']);
                $batchSubjectModel->weekly_frequency = $batchSubject['weekly_frequency'] ?? $batchSubjectModel->weekly_frequency;
                $batchSubjectModel->teacher_id = $batchSubject['teacher_id'];
                $batchSubjectModel->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Batch subjects updated successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while updating batch subjects.');
        }

    }

    public static function searchTeachers(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
        ]);

        return Teacher::whereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->input('search')}%");
        })->with(['user'])->get()->take(7);
    }
}
