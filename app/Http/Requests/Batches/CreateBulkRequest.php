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
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $batches = $this->input('batches');

            // Get School year
            $schoolYear = SchoolYear::find($batches['school_year_id']);

            if (isset($schoolYear->end_date)) {
                $validator->errors()->add('batches', 'School year is not active');
            }

            foreach ($batches['grade'] as $batch) {
                // check if level exists
                $level = Level::find($batch['level_id']);

                if (! $level) {
                    $validator->errors()->add('batches', 'Level does not exist');
                }
                // Loop sections and check if level-section exists
                foreach ($batch['sections'] as $section) {
                    $batch = Batch::where('level_id', $level->id)
                        ->where('section', $section)
                        ->first();

                    if ($batch) {
                        $validator->errors()->add('batches', $level->name.$section.' exists!');
                    }
                }
            }
        });
    }
}
