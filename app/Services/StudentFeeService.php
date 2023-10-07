<?php

namespace App\Services;

use App\Events\StudentFeeEvent;
use App\Models\Fee;
use App\Models\LevelCategory;
use App\Models\Penalty;
use App\Models\SchoolYear;
use App\Models\StudentTuition;
use App\Models\StudentTuitionPenalty;
use Carbon\Carbon;
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

    public function prepareTuitionPayment(StudentTuition $studentTuition): bool
    {
        /**
         * Steps
         *
         * 1. Check if the student tuition is already paid
         * 2. Check if the student tuition is already overdue
         * 3. Calculate the penalty
         * 4. Update Tuition Penalty and Tuition Amount
         */

        // 1. Check if the student tuition is already paid
        if ($studentTuition->status === StudentTuition::STATUS_PAID) {
            return false;
        }

        // 2. Check if the student tuition is already overdue
        if ($studentTuition->status === StudentTuition::STATUS_UNPAID && $studentTuition->load('fee')->fee->due_date < now()) {

            // 3. Calculate the penalty
            $penalty = $this->calculateTuitionPenalty($studentTuition->load('fee.penalty'));
            StudentTuitionPenalty::updateOrCreate([
                'student_tuition_id' => $studentTuition->id,
                'penalty_id' => $studentTuition->fee->penalty_id,
            ], [
                'amount' => $penalty,
            ]);
        }

        return true;
    }

    protected function calculateTuitionPenalty(StudentTuition $studentTuition): float
    {
        $today = today();
        $due_date = Carbon::createFromDate($studentTuition->fee->due_date);
        $penaltyType = $studentTuition->fee->penalty->type;

        return match ($penaltyType) {
            Penalty::TYPE_PERCENTAGE => $this->calculatePercentagePenalty($studentTuition->fee),
            Penalty::TYPE_FLAT_RATE => $this->calculateFlatRatePenalty($studentTuition->fee),
            Penalty::TYPE_DAILY => $this->calculateDailyPenalty($studentTuition->fee, $today, $due_date),
        };
    }

    private function calculatePercentagePenalty(Fee $fee): float
    {
        $penaltyPercentage = $fee->penalty->amount / 100;

        return $fee->amount * $penaltyPercentage;
    }

    private function calculateFlatRatePenalty(Fee $fee): float
    {
        return $fee->penalty->amount;
    }

    private function calculateDailyPenalty(Fee $fee, Carbon $to, Carbon $from): float
    {
        $dailyPenalty = $fee->penalty->amount;
        $overdueDays = $to->diffInDays($from);

        return $overdueDays * $dailyPenalty;
    }
}
