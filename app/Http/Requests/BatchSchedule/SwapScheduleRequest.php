<?php

namespace App\Http\Requests\BatchSchedule;

use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use App\Models\Teacher;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class SwapScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'schedule_a' => 'exists:batch_schedules,id',
            'schedule_b' => 'exists:batch_schedules,id',
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->ensureSwapMaintainsOverlapConstraints();
        });
    }

    private function ensureSwapMaintainsOverlapConstraints(): void
    {
        $teacher = Teacher::find(BatchSchedule::find($this->input('schedule_a'))->load('batchSubject')->batchSubject->teacher_id);
        $batchSchedule = BatchSchedule::find($this->input('schedule_b'));
        $this->ensureTeacherScheduleDoesNotOverlap($teacher, $batchSchedule);

        $teacher = Teacher::find(BatchSchedule::find($this->input('schedule_b'))->load('batchSubject')->batchSubject->teacher_id);
        $batchSchedule = BatchSchedule::find($this->input('schedule_a'));
        $this->ensureTeacherScheduleDoesNotOverlap($teacher, $batchSchedule);
    }

    private function ensureTeacherScheduleDoesNotOverlap(Teacher $teacher, BatchSchedule $batchSchedule): void
    {
        $batchSchedule = $batchSchedule->load('schoolPeriod');
        $teacherSubjectSchedules = BatchSubject::with('schedule.schoolPeriod')
            ->where('teacher_id', $teacher->id)
            ->get();

        $teacherSubjectSchedules->pluck('schedule')->each(function ($teacherSubjectSchedule) use ($batchSchedule) {
            $teacherSubjectSchedule->where('day_of_week', $batchSchedule->day_of_week)
                ->each(function ($teacherSchedule) use ($teacherSubjectSchedule, $batchSchedule) {
                    $toScheduleTime = Carbon::createFromDate($batchSchedule->schoolPeriod->start_time);
                    $teacherScheduleTime = Carbon::createFromDate($teacherSchedule->schoolPeriod->start_time);

                    if ($toScheduleTime->diffInMinutes($teacherScheduleTime) < $batchSchedule->schoolPeriod->duration) {
                        $overlappingBatchSubject = $teacherSubjectSchedule->load('batch.level', 'batchSubject.subject');
                        $this->validator->errors()->add(
                            'schedule_a',
                            'This schedule overlaps with the teacher\'s Grade'
                        );
                    }
                });
        });
    }
}
