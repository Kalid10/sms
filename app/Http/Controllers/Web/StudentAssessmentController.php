<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\StudentAssessments\InsertStudentsAssessmentRequest;
use App\Jobs\InsertStudentsAssessmentsJob;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;

class StudentAssessmentController extends Controller
{
    use HasFactory;

    public function insert(Assessment $assessment, InsertStudentsAssessmentRequest $request): RedirectResponse
    {
        InsertStudentsAssessmentsJob::dispatch($request->validated('points'), $assessment);

        return redirect()
            ->back()
            ->with('success', 'Your students\'s assessments are being inserted. We will notify you when it\'s done.');
    }
}
