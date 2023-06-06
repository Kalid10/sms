<?php

namespace App\Observers;

use App\Models\Quarter;
use App\Models\Semester;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;

class ConductObserver
{
    public function created(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->conduct) {
            $this->updateAverageQuarterlyConduct($studentSubjectGrade->student_id);
            $this->updateAverageSemesterConduct($studentSubjectGrade->student_id);
        }
    }

    public function updated(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->wasChanged('conduct')) {
            $this->updateAverageQuarterlyConduct($studentSubjectGrade->student_id);
            $this->updateAverageSemesterConduct($studentSubjectGrade->student_id);
        }
    }

    private function updateAverageQuarterlyConduct($studentId): void
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

    private function updateAverageSemesterConduct($studentId): void
    {
        $semesterQuarterIds = Semester::getActiveSemester()->load('quarters')->quarters->pluck('id');

        $quarterlyConducts = StudentGrade::where([
            ['gradable_type', Quarter::class],
            ['student_id', $studentId]])
            ->whereIn('gradable_id', $semesterQuarterIds)
            ->pluck('conduct');

        $total = 0;
        $count = 0;
        $gradeMap = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0];

        foreach ($quarterlyConducts as $conduct) {
            if (isset($gradeMap[$conduct])) {
                $total += $gradeMap[$conduct];
                $count++;
            }
        }

        if ($count === 0) {
            $average = null;
        } else {
            $average = $total / $count;
        }

        $averageConduct = null;
        if ($average !== null) {
            $roundedAverage = round($average);
            $gradeMapFlipped = array_flip($gradeMap);
            $averageConduct = $gradeMapFlipped[$roundedAverage];
        }

        $studentGrade = StudentGrade::where([
            ['gradable_type', Semester::class],
            ['gradable_id', Semester::getActiveSemester()->id],
            ['student_id', $studentId]])->first();
        $studentGrade->conduct = $averageConduct;
        $studentGrade->save();
    }
}
