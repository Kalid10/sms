<?php

namespace App\Http\Controllers\Web\Assessments;

use App\Http\Controllers\Web\Controller;
use App\Jobs\CreateMassAssessmentJob;
use App\Models\Assessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminAssessmentController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'level_category_ids' => 'required|array',
            'level_category_ids.*' => 'required|integer|exists:level_categories,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'due_date' => 'required|date',
            'title' => 'required|string',
            'description' => 'string',
            'maximum_point' => 'required|integer|min:1|max:100',
            'lesson_plan_id' => 'nullable|exists:lesson_plans,id',
            'status' => 'nullable|string|in:draft,published,scheduled',
        ]);

        CreateMassAssessmentJob::dispatch($request->all(), auth()->user()->id);

        return redirect()->back()->with('success', 'Assessment is being created, we will notify you once it is done.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'assessment_id' => 'required|integer|exists:assessments,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'maximum_point' => 'required|integer',
            'status' => 'required|string|in:draft,published,closed,marking,completed,scheduled',
            'due_date' => 'required|date',
        ]);

        $assessment = Assessment::find($validatedData['assessment_id']);
        $assessment->update($validatedData);

        return redirect()->back()->with('success', 'Assessment updated.');
    }
}
