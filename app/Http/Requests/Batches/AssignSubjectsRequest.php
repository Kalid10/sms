<?php

namespace App\Http\Requests\Batches;

use App\Models\Batch;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignSubjectsRequest extends FormRequest
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
            'batches_subjects' => 'required|array',
            'batches_subjects.*.batch_id' => 'required|integer|exists:batches,id',
            'batches_subjects.*.subject_ids' => 'required|array',
            'batches_subjects.*.subject_ids.*.id' => 'integer|exists:subjects,id',
            'batches_subjects.*.subject_ids.*.selected' => 'nullable|boolean',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->batches_subjects as $batchData) {
                $batchId = $batchData['batch_id'];
                $batch = Batch::find($batchId);

                if (isset($batch->schoolYear->end_date)) {
                    $validator->errors()->add('batches_subjects', 'The batch with ID '.$batchId.' is not active.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'batches_subjects.*.batch_id.exists' => 'The batch with ID :input does not exist.',
            'batches_subjects.*.subject_ids.*.exists' => 'The subject with ID :input does not exist.',
        ];
    }
}
