<?php

namespace App\Services;

use App\Models\BatchStudent;
use App\Models\Quarter;
use App\Models\Semester;

class StudentService
{
    public static function getBatchStudents(int $batchId, ?string $search = '', int $batchSubjectId = null)
    {
        $batchStudents = BatchStudent::where('batch_id', $batchId)
            ->with('student.user.absentee', 'student.batches')
            ->whereHas('student.user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate(10)
            ->appends(['batch_subject_id' => $batchSubjectId, 'batch_id' => $batchId]);

        $batchStudents->getCollection()->transform(function ($student) use ($batchSubjectId) {
            if ($batchSubjectId) {
                $studentBatchSubjectGrade = $student->student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first();
                $student->batch_subject_rank = $studentBatchSubjectGrade?->rank;
                $student->conduct = $studentBatchSubjectGrade?->conduct;
            }
            $student->attendance_percentage = 100 - $student->student->absenteePercentage();

            $student->quarterly_grade = $student->student->grades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first();

            $student->semester_grade = $student->student->grades()->where([
                ['gradable_type', Semester::class],
                ['gradable_id', Semester::getActiveSemester()->id],
            ])->first();

            return $student;
        });

        return $batchStudents;
    }

    public static function getBatchSubjectTopStudents($batchSubject)
    {
        return $batchSubject->studentGrades()->where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])
            ->whereNotNull('rank')
            ->orderBy('rank', 'ASC')
            ->with('student.user', 'student.batches')
            ->get()
            ->take(5)
            ->each(function ($student) {
                $student->attendance_percentage = 100 - $student->student->absenteePercentage();
            });
    }

    public static function getBatchSubjectBottomStudents($batchSubject)
    {
        return $batchSubject->studentGrades()->where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])
            ->whereNotNull('rank')
            ->orderBy('rank', 'DESC')
            ->with('student.user', 'student.batches')
            ->get()
            ->take(5)
            ->each(function ($student) {
                $student->attendance_percentage = 100 - $student->student->absenteePercentage();
            });
    }
}
