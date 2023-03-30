<?php

namespace App\Http\Requests\Batches;

use App\Models\Batch;
use App\Models\Level;
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
            'batches' => 'required|array',
            'batches.*.level_id' => 'required|exists:levels,id',
            'batches.*.no_of_sections' => 'required|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $batches = $this->input('batches');

            // Get School year
            $schoolYear = SchoolYear::whereNull('end_date')->first();

            if (isset($schoolYear->end_date)) {
                $validator->errors()->add('batches', 'School year is not active');
            }

            // Check if batch already exists
            foreach ($batches as $batch) {
                $levelId = $batch['level_id'];
                $noOfSections = $batch['no_of_sections'];

                if (Batch::where('level_id', $levelId)
                        ->where('school_year_id', $schoolYear->id)->count() === $noOfSections) {
                    $validator->errors()->add('batches', 'Batch already exists');
                }
            }

            foreach ($batches as $batch) {
                // check if level exists
                $level = Level::find($batch['level_id']);

                if (! $level) {
                    $validator->errors()->add('batches', 'Level does not exist');
                }
            }
        });
    }
}
