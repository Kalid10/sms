<?php

namespace App\Services;

use App\Models\BatchStudent;
use App\Models\Quarter;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAssessmentsGrade;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class StudentService
{
    public static function getBatchStudents(int $batchId, ?string $search = '', int $batchSubjectId = null)
    {
        $batchStudents = BatchStudent::where('batch_id', $batchId)
            ->with('student.user.absentee', 'student.batches', 'student.flags')
            ->whereHas('student.user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate(10)
            ->appends(['batch_subject_id' => $batchSubjectId, 'batch_id' => $batchId]);

        $batchStudents->getCollection()->transform(function ($student) use ($batchSubjectId) {
            if ($batchSubjectId) {
                $studentBatchSubjectGrade = $student->student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first();
                $student->rank = $studentBatchSubjectGrade?->rank;
                $student->conduct = $studentBatchSubjectGrade?->conduct;
                $student->attendance_percentage = 100 - $student->student->absenteePercentage($batchSubjectId);
                $student->quarterly_grade = $student->student->studentSubjectGrades()->where([
                    ['gradable_type', Quarter::class],
                    ['gradable_id', Quarter::getActiveQuarter()->id],
                    ['batch_subject_id', $batchSubjectId],
                ])->first()?->load('gradeScale');

                $student->semester_grade = $student->student->studentSubjectGrades()->where([
                    ['gradable_type', Semester::class],
                    ['gradable_id', Semester::getActiveSemester()->id],
                    ['batch_subject_id', $batchSubjectId],
                ])->first();
            } else {
                $studentGrade = $student->student->grades()->where([
                    ['gradable_type', Quarter::class],
                    ['gradable_id', Quarter::getActiveQuarter()->id],
                ])->first();
                $student->rank = $studentGrade?->rank;
                $student->conduct = $studentGrade?->conduct;
                $student->attendance_percentage = 100 - $student->student->absenteePercentage();
                $student->quarterly_grade = $studentGrade;
                $student->semester_grade = $student->student->grades()->where([
                    ['gradable_type', Semester::class],
                    ['gradable_id', Semester::getActiveSemester()->id],
                ])->first();
            }

            return $student;
        });

        return $batchStudents;
    }

    public static function getAllStudents(Request $request): LengthAwarePaginator
    {
        $searchKey = $request->input('search');
        $perPage = $request->input('per_page', 15);

        return Student::with([
            'user:id,name,email,phone_number,gender',
            'currentBatch.level',
        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })->paginate($perPage);
    }

    public static function getStudentDetail($studentId, $batch)
    {
        if (! $studentId) {
            return null;
        }

        $batchSubjects = $batch->load('subjects.subject')->subjects;

        $studentsGrade = StudentSubjectGrade::where([
            ['student_id', $studentId],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->get();

        if (count($batchSubjects) > $studentsGrade->count()) {
            $batchSubjects->each(function ($batchSubject) use ($studentsGrade, $studentId) {
                if (! $studentsGrade->contains('batch_subject_id', $batchSubject->id)) {
                    $studentsGrade->push(StudentSubjectGrade::create([
                        'student_id' => $studentId,
                        'batch_subject_id' => $batchSubject->id,
                        'gradable_type' => Quarter::class,
                        'gradable_id' => Quarter::getActiveQuarter()->id,
                    ]));
                }
            });
        }

        return StudentSubjectGrade::where([
            ['student_id', $studentId],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->with(['batchSubject.teacher.user', 'batchSubject.subject', 'batchSubject.assessmentsGrades' => function ($query) use ($studentId) {
            $query->where([
                ['student_id', $studentId],
            ]);
        }, 'batchSubject.assessmentsGrades.assessmentType', 'batchSubject.assessmentsGrades.gradeScale'])->get();
    }

    public static function getBatchSubjectAssessmentDetail($studentId, $batchSubjectId)
    {
        return StudentAssessmentsGrade::where([
            ['batch_subject_id', $batchSubjectId],
            ['student_id', $studentId],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->with('student.user', 'batchSubject.subject')->get();
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

    public static function getBatchTopStudents($batchStudents)
    {
        return StudentGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])
            ->whereIn('student_id', $batchStudents)
            ->whereNotNull('rank')
            ->orderBy('rank', 'ASC')
            ->with('student.user', 'student.batches')
            ->get()
            ->take(5)
            ->each(function ($student) {
                $student->attendance_percentage = 100 - $student->student->absenteePercentage();
            });
    }

    public static function getBatchBottomStudents($batchStudents)
    {
        return StudentGrade::where([
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])
            ->whereIn('student_id', $batchStudents)
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
