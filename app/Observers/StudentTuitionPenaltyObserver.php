<?php

namespace App\Observers;

use App\Models\StudentTuitionPenalty;

class StudentTuitionPenaltyObserver
{
    /**
     * Handle the StudentTuitionPenalty "created" event.
     */
    public function created(StudentTuitionPenalty $studentTuitionPenalty): void
    {
        $studentTuition = $studentTuitionPenalty->load('studentTuition.fee')->studentTuition;
        $fee = $studentTuition->fee;

        $studentTuitionPenalty->load('studentTuition.fee')
            ->studentTuition
            ->update([
                'amount' => $fee->amount + $studentTuitionPenalty->amount,
            ]);

        $studentTuitionPenalty->studentTuition->save();
    }

    /**
     * Handle the StudentTuitionPenalty "updated" event.
     */
    public function updated(StudentTuitionPenalty $studentTuitionPenalty): void
    {
        $studentTuition = $studentTuitionPenalty->load('studentTuition.fee')->studentTuition;
        $fee = $studentTuition->fee;

        $studentTuitionPenalty->load('studentTuition.fee')
            ->studentTuition
            ->update([
                'amount' => $fee->amount + $studentTuitionPenalty->amount,
            ]);

        $studentTuitionPenalty->studentTuition->save();
    }

    /**
     * Handle the StudentTuitionPenalty "deleted" event.
     */
    public function deleted(StudentTuitionPenalty $studentTuitionPenalty): void
    {
        //
    }

    /**
     * Handle the StudentTuitionPenalty "restored" event.
     */
    public function restored(StudentTuitionPenalty $studentTuitionPenalty): void
    {
        //
    }

    /**
     * Handle the StudentTuitionPenalty "force deleted" event.
     */
    public function forceDeleted(StudentTuitionPenalty $studentTuitionPenalty): void
    {
        //
    }
}
