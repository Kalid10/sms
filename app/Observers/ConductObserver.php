<?php

namespace App\Observers;

use App\Models\Quarter;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;

class ConductObserver
{
    public function created(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->conduct) {
            $this->updateAverageConduct($studentSubjectGrade->student_id);
        }
    }

    public function updated(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->wasChanged('conduct')) {
            $this->updateAverageConduct($studentSubjectGrade->student_id);
        }
    }

    private function updateAverageConduct($studentId): void
    {
        $studentSubjectGrades = StudentSubjectGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['student_id', $studentId]])->get();

        $total = 0;

        $gradeMap = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0];

        foreach ($studentSubjectGrades as $grade) {
            if (isset($gradeMap[$grade->conduct])) {
                $total += $gradeMap[$grade->conduct];
            }
        }

        $average = $total / count($studentSubjectGrades);

        // round the average
        $roundedAverage = round($average);

        // map the numerical value back to a conduct grade
        $gradeMapFlipped = array_flip($gradeMap);
        $averageConduct = $gradeMapFlipped[$roundedAverage];

        $studentGrade = StudentGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['student_id', $studentId]])->first();
        $studentGrade->conduct = $averageConduct;
        $studentGrade->save();
    }
}
