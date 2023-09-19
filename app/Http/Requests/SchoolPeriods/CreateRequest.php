<?php

namespace App\Http\Requests\SchoolPeriods;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'school_periods' => 'required|array',
            'school_periods.*.no_of_periods' => 'required|integer|gt:0',
            'school_periods.*.minutes_per_period' => 'required|integer|gt:0',
            'school_periods.*.start_time' => 'required|date_format:H:i',
            'school_periods.*.custom_periods' => 'nullable|array',
            'school_periods.*.custom_periods.*.name' => 'required|string',
            'school_periods.*.custom_periods.*.before_period' => 'required|integer|gt:0',
            'school_periods.*.custom_periods.*.duration' => 'required|integer',
            'school_periods.*.level_category_ids' => 'required|array',
            'school_periods.*.level_category_ids.*' => 'required|integer|distinct:strict|exists:level_categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'school_periods.*.custom_periods.*.name.required' => 'Custom period name is required',
            'school_periods.*.custom_periods.*.before_period.required' => 'Before period is required',
            'school_periods.*.custom_periods.*.duration.required' => 'Duration is required',
        ];
    }
}
