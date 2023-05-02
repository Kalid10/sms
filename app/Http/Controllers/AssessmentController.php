<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\CreateAssessmentRequest;
use App\Models\Assessment;
use App\Models\Quarter;

class AssessmentController extends Controller
{
    public function create(CreateAssessmentRequest $request)
    {
        $validated = $request->validated();

        $assessment = Assessment::create([
            'batch_subject_id' => $validated['batch_subject_id'],
            'assessment_type_id' => $validated['assessment_type_id'],
            'due_date' => $validated['due_date'],
            'quarter_id' => Quarter::getActiveQuarter()->id,
        ]);

        return redirect()->back()->with('success', 'Assessment created.');
    }
}
