<?php

namespace App\Services;

use App\Events\StudentFeeEvent;
use App\Models\Fee;
use App\Models\LevelCategory;
use App\Models\SchoolYear;
use App\Models\StudentTuition;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentFeeService
{
    public function createStudentFee(array $data, Collection $fees): ?array
    {
        try {
            DB::transaction(function () use ($data, $fees) {
                foreach ($data['level_category_ids'] as $level_category_id) {
                    if ($data['is_student_tuition_fee'] && $data['is_active']) {
                        $levelCategories = LevelCategory::find($level_category_id)->load([
                            'levels.batches' => function ($query) {
                                return $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                            },
                            'levels.batches.students',
                        ]);

                        $tuitions = [];

                        foreach ($fees as $fee) {
                            if ($fee->level_category_id !== $level_category_id) {
                                continue;
                            }

                            foreach ($levelCategories->levels as $level) {
                                foreach ($level->batches as $batch) {
                                    foreach ($batch->students as $student) {
                                        $tuitions[] = [
                                            'student_id' => $student->student_id,
                                            'fee_id' => $fee->id,
                                            'amount' => $fee->amount,
                                            'status' => StudentTuition::STATUS_UNPAID,
                                        ];
                                    }
                                }
                            }
                        }
                        StudentTuition::insert($tuitions);  // Bulk insert
                    }
                }
            }, 5);  // Number of attempts to run the block of code within a DB transaction

            return event(new StudentFeeEvent('success', 'You have successfully created new fees!'));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            // Delete the fees
            Fee::whereIn('id', $fees->pluck('id'))->delete();

            return event(new StudentFeeEvent('error', 'Error while creating new fees, please try again later.'));
        }
    }
}
