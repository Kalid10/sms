<?php

namespace App\Observers;

use App\Models\BatchGrade;
use App\Models\BatchSubjectGrade;
use App\Models\Quarter;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;

class ConductObserver
{
    private static $batchSubjectId = null;

    public function created(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->conduct) {
            $this->updateAverageQuarterlyStudentConduct($studentSubjectGrade->student_id);
            $this->updateAverageSemesterStudentConduct($studentSubjectGrade->student_id);
        }
    }

    public function updated(StudentSubjectGrade $studentSubjectGrade): void
    {
        if ($studentSubjectGrade->isDirty('conduct')) {
            self::$batchSubjectId = $studentSubjectGrade->batch_subject_id;
            $this->updateAverageQuarterlyStudentConduct($studentSubjectGrade->student_id);
            $this->updateAverageSemesterStudentConduct($studentSubjectGrade->student_id);
        }
    }

    private function updateAverageQuarterlyStudentConduct($studentId): void
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

        $this->updateAverageQuarterlyBatchSubjectConduct($studentId);
    }

    private function updateAverageSemesterStudentConduct($studentId): void
    {
        $semesterQuarterIds = Semester::getActiveSemester()->load('quarters')->quarters->pluck('id');

        $quarterlyConducts = StudentGrade::where([
            ['gradable_type', Quarter::class],
            ['student_id', $studentId]])
            ->whereIn('gradable_id', $semesterQuarterIds)
            ->pluck('conduct');

        $averageConduct = $this->getSemesterConduct($quarterlyConducts);

        $studentGrade = StudentGrade::where([
            ['gradable_type', Semester::class],
            ['gradable_id', Semester::getActiveSemester()->id],
            ['student_id', $studentId]])->first();
        $studentGrade->conduct = $averageConduct;
        $studentGrade->save();
    }

    private function updateAverageQuarterlyBatchSubjectConduct($studentId): void
    {
        $student = Student::find($studentId);
        $batch = $student->activeBatch();

        $studentIds = $batch->students->pluck('student_id');
        $studentsConduct = StudentSubjectGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['batch_subject_id', self::$batchSubjectId],
        ]
        )->whereIn('student_id', $studentIds)->get()->pluck('conduct');

        $averageConduct = $this->getQuarterlyConduct($studentsConduct);

        $batchGrade = BatchSubjectGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['batch_id', $batch->id]])->first();
        $batchGrade->conduct = $averageConduct;
        $batchGrade->save();
        $this->updateAverageQuarterlyBatchConduct($batch->id);
    }

    private function updateAverageQuarterlyBatchConduct($batchId): void
    {
        $batchSubjectsConduct = BatchSubjectGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['batch_id', $batchId]]
        )->get()->pluck('conduct');

        $averageConduct = $this->getQuarterlyConduct($batchSubjectsConduct);

        $batchGrade = BatchGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
            ['batch_id', $batchId]])->first();
        $batchGrade->conduct = $averageConduct;
        $batchGrade->save();

        $this->updateAverageSemesterBatchSubjectConduct(batchId: $batchId);
    }

    private function updateAverageSemesterBatchSubjectConduct($batchId): void
    {
        $semesterQuarterIds = Semester::getActiveSemester()->load('quarters')->quarters->pluck('id');

        $quarterlyConducts = BatchSubjectGrade::where([
            ['gradable_type', Quarter::class],
            ['batch_id', $batchId],
        ])->whereIn('gradable_id', $semesterQuarterIds)->pluck('conduct');

        $averageConduct = $this->getSemesterConduct($quarterlyConducts);

        $studentGrade = BatchSubjectGrade::where([
            ['gradable_type', Semester::class],
            ['gradable_id', Semester::getActiveSemester()->id],
            ['batch_id', $batchId]])->first();
        $studentGrade->conduct = $averageConduct;
        $studentGrade->save();
        $this->updateAverageSemesterBatchConduct(batchId: $batchId, semesterQuarterIds: $semesterQuarterIds);
    }

    private function updateAverageSemesterBatchConduct($batchId, $semesterQuarterIds): void
    {
        $quarterlyConducts = BatchGrade::where([
            ['gradable_type', Quarter::class],
            ['batch_id', $batchId],
        ])->whereIn('gradable_id', $semesterQuarterIds)->pluck('conduct');

        $averageConduct = $this->getSemesterConduct($quarterlyConducts);

        $studentGrade = BatchGrade::where([
            ['gradable_type', Semester::class],
            ['gradable_id', Semester::getActiveSemester()->id],
            ['batch_id', $batchId]])->first();
        $studentGrade->conduct = $averageConduct;
        $studentGrade->save();
    }

    private function getQuarterlyConduct($batchSubjectsConduct): ?string
    {
        $totalCount = $batchSubjectsConduct->count();
        $batchSubjectsConduct = $batchSubjectsConduct->filter(function ($value) {
            return ! is_null($value);
        });

        $nonNullPercentage = ($batchSubjectsConduct->count() / $totalCount) * 100;

        // To set batch conduct to null if 75% of the students have no conduct grade
        if ($nonNullPercentage < 75) {
            return null;
        }

        // Mapping the conduct grades to numerical values
        $gradeMap = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, 'F' => 0];
        $numericalConducts = $batchSubjectsConduct->map(function ($value, $key) use ($gradeMap) {
            return $gradeMap[$value];
        });

        // Calculate the average
        $average = $numericalConducts->avg();

        // Round the average and map it back to a conduct grade
        $roundedAverage = round($average);
        $gradeMapFlipped = array_flip($gradeMap);

        return $gradeMapFlipped[$roundedAverage];
    }

    private function getSemesterConduct($quarterlyConducts): string|int|null
    {
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

        return $averageConduct;
    }
}
