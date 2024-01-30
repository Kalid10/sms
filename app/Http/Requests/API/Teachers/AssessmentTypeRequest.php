<?php

namespace App\Http\Requests\API\Teachers;

class AssessmentTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $teacher = $this->teacher();

        if ($this->route('assessmentType')) {

            $teacherAssessmentTypeIds = $teacher
                ->load('batchSubjects.batch.level.levelCategory.assessmentTypes')
                ->batchSubjects
                ->pluck('batch.level.levelCategory.assessmentTypes')
                ->flatten()
                ->unique('id')
                ->pluck('id');

            return $teacherAssessmentTypeIds->contains($this->route('assessmentType')->id);
        }

        return parent::authorize();
    }

    public function rules(): array
    {
        return [
            'active' => 'nullable|boolean',
            'level_category_id' => 'nullable|exists:level_categories,id',
            'with_admin_controlled' => 'nullable|boolean',
        ];
    }
}
