<?php

namespace App\Http\Requests\BatchSubjects;

use App\Models\Batch;
use App\Models\BatchSubject;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SetBatchSubjectWeeklyFrequencyRequest extends FormRequest
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
            'batch_subject' => 'exists:batch_subjects,id|required',
            'frequency' => 'integer|required|min:1',
            'all_sections' => 'boolean|required',
        ];
    }

    /**
     * @return void
     *
     * Invalidate if the given frequency exceeds the available schedule slots for the given batch.
     */
    private function ensureFrequencyDoesNotExceedBatchScheduleSlots(Batch $batch): void
    {
        if ($this->input('frequency') > $batch->availableScheduleSlots()) {
            $this->validator->errors()->add(
                'frequency',
                'The given weekly session count exceeds the available schedule slots ('.
                $batch->availableScheduleSlots().
                ') for '.
                $batch->load('level')->level->name.
                ' '.
                $batch->section.'.'
            );
        }
    }

    private function ensureValidationForAllSections(): void
    {
        $batchSubject = BatchSubject::find($this->input('batch_subject'))->load('batch');
        $batch = $batchSubject->batch;

        if ($this->input('allSections')) {
            $allBatches = $batchSubject->load('subject.activeBatches')
                ->subject->activeBatches
                ->where('level_id', $batch->level_id);

            $allBatches->each(function ($batch) {
                $this->ensureFrequencyDoesNotExceedBatchScheduleSlots($batch);
            });
        } else {
            $this->ensureFrequencyDoesNotExceedBatchScheduleSlots($batch);
        }
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->ensureValidationForAllSections();
        });
    }
}
