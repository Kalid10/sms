<?php

namespace App\Http\Requests\Batches;

use App\Models\Batch;
use App\Models\SchoolYear;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBulkRequest extends FormRequest
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
            'batches' => 'required|array|min:1',
            'batches.*.level_id' => 'required',
            'batches.*.no_of_sections' => 'required|integer|min:1',
            'batches.*.min_students' => 'nullable|integer|min:5',
            'batches.*.max_students' => 'nullable|integer|min:5',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validator->sometimes('batches.*.level_id', 'exists:levels,id', function () {
                return is_int($this->input('level_id'));
            });

            $validator->sometimes('batches.*.level_id.name', 'required|unique:levels,name|string', function () {
                return is_array($this->input('level_id'));
            });

            $batches = $this->input('batches');

            // Get School year
            $schoolYear = SchoolYear::whereNull('end_date')->first();

            if (! $schoolYear) {
                return $validator->errors()->add('batches', 'School year is not active');
            }

            // If input('level_id') is an array, check if batch already exists
            if (is_int($this->input('level_id'))) {
                foreach ($batches as $batch) {
                    $levelId = $batch['level_id'];
                    $noOfSections = $batch['no_of_sections'];

                    if (Batch::where('level_id', $levelId)
                            ->where('school_year_id', $schoolYear->id)->count() === $noOfSections) {
                        $validator->errors()->add('batches', 'Batch already exists');
                    }
                }
            }
        });
    }
}
