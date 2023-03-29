<?php

namespace App\Http\Requests\Batches;

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
            $schoolYear = SchoolYear::whereNull('end_date')->first();

            if (isset($schoolYear->end_date)) {
                return $validator->errors()->add('batches', 'School year is not active');
            }

            foreach ($batches['grade'] as $batch) {
                // check if level exists
                $level = Level::find($batch['level_id']);

                if (! $level) {
                    return $validator->errors()->add('batches', 'Level does not exist');
                }
            }
        });
    }
}
