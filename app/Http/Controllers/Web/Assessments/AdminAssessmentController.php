<?php

namespace App\Http\Controllers\Web\Assessments;

use App\Http\Controllers\Web\Controller;
use App\Models\Assessment;
use App\Models\AssessmentMapping;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolYear;
use Carbon\Carbon;
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

        $levelCategoryIds = $request->input('level_category_ids');

        // Loop through each level category id and get level ids
        foreach ($levelCategoryIds as $levelCategoryId) {
            $levelIds = Level::where('level_category_id', $levelCategoryId)->pluck('id');

            // Loop through each level id and get batch ids
            foreach ($levelIds as $levelId) {
                $batchIds = Batch::where('level_id', $levelId)->pluck('id');

                // Loop through each batch id and get batch subject ids
                foreach ($batchIds as $batchId) {
                    $batchSubjectIds = BatchSubject::where('batch_id', $batchId)->pluck('id');

                    // Loop through each batch subject id and create assessment
                    foreach ($batchSubjectIds as $batchSubjectId) {
                        $batchSubject = BatchSubject::find($batchSubjectId);

                        $batchSubject->assessments()->create(array_merge(

                            $request->only([
                                'assessment_type_id',
                                'due_date',
                                'title',
                                'description',
                                'maximum_point',
                                'lesson_plan_id',
                                'status',
                            ]),
                            [
                                'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
                                'quarter_id' => Quarter::getActiveQuarter()->id,
                                'created_by' => auth()->id(),
                            ]
                        ));
                    }
                }
            }
            // Create an assessment mapping
            AssessmentMapping::create([
                'due_date' => Carbon::parse($request->input('due_date')),
                'user_id' => auth()->id(),
                'level_category_id' => $levelCategoryId,
                'assessment_type_id' => $request->input('assessment_type_id'),
            ]);
        }

        return redirect()->back()->with('success', 'Assessment created.');
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

    public function deleteas(Assessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()->back()->with('success', 'Assessment deleted.');
    }
}
