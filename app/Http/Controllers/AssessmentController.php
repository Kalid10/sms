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

        Assessment::create(array_merge(
            $request->validated(),
            ['quarter_id' => Quarter::getActiveQuarter()->id]
        ));

        return redirect()->back()->with('success', 'Assessment created.');
    }
}
