<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\AssessmentTypeRequest;
use App\Http\Resources\Teachers\AssessmentTypeCollection;
use App\Http\Resources\Teachers\AssessmentTypeResource;
use App\Models\AssessmentType;
use App\Models\SchoolYear;

class AssessmentTypeController extends Controller
{
    /**
     * Return the authenticated teacher's assessments.
     */
    public function index(AssessmentTypeRequest $request, ?AssessmentType $assessmentType): AssessmentTypeResource|AssessmentTypeCollection
    {
        if ($assessmentType->exists) {
            return new AssessmentTypeResource($assessmentType);
        }

        return new AssessmentTypeCollection(parent::teacher()
            ->load('batchSubjects.batch.level.levelCategory.assessmentTypes')
            ->batchSubjects
            ->pluck('batch.level.levelCategory.assessmentTypes')
            ->flatten()
            ->unique('id')
            ->filter(function ($assessmentType) {
                return $assessmentType->school_year_id === SchoolYear::getActiveSchoolYear()->id;
            })
            ->filter(function ($assessmentType) use ($request) {
                if ($request->has('with_admin_controlled') && $request->input('with_admin_controlled')) {
                    return true;
                }

                return ! $assessmentType->is_admin_controlled;
            })
            ->filter(function ($assessmentType) use ($request) {
                if ($request->has('level_category_id')) {
                    return $assessmentType->level_category_id === $request->integer('level_category_id');
                }

                return true;
            })
        );
    }
}
