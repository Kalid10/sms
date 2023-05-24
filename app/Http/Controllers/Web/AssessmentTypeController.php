<?php

namespace App\Http\Controllers\Web;

use App\Models\AssessmentType;
use App\Models\LevelCategory;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentTypeController extends Controller
{
    public function index(): Response
    {
        $assessmentTypes = AssessmentType::with('levelCategory', 'schoolYear')->get();

        $levelCategories = LevelCategory::all();

        return Inertia::render('Assessment/AssessmentTypes/Index', [
            'assessment_types' => $assessmentTypes,
            'level_categories' => $levelCategories,
        ]);
    }

    public function create()
    {
        $validated = request()->validate([
            'name' => 'required|string',
            'percentage' => 'required|numeric',
            'customizable' => 'required|boolean',
            'min_assessments' => 'required_if:customizable,true|nullable|numeric|lt:max_assessments',
            'max_assessments' => 'required_if:customizable,true|nullable|numeric|gte:min_assessments',
            'level_category_id' => 'required|array|min:1',
            'level_category_id.*' => 'numeric|exists:level_categories,id',
        ]);

        $level_category_ids = is_array($validated['level_category_id']) ? $validated['level_category_id'] : [$validated['level_category_id']];

        foreach ($level_category_ids as $level_category_id) {
            $schoolYear = SchoolYear::getActiveSchoolYear()->id;
            if ($schoolYear) {
                AssessmentType::create(array_merge($validated, ['level_category_id' => $level_category_id, 'school_year_id' => $schoolYear]));
            } else {
                return redirect()->back()->with('error', 'There is no active school year. Please create one.');
            }
        }

        return redirect()->back()->with('success', 'Assessment type created successfully.');
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $validated = request()->validate([
                'name' => 'required|string',
                'percentage' => 'required|numeric',
                'customizable' => 'required|boolean',
                'min_assessments' => 'required_if:customizable,true|nullable|numeric|lt:max_assessments',
                'max_assessments' => 'required_if:customizable,true|nullable|numeric|gte:min_assessments',
                'level_category_id' => 'required|numeric|exists:level_categories,id',
            ]);

            $assessmentType = AssessmentType::find($request->id);

            $assessmentType->update($validated);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->back()->with('success', 'Assessment type updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        AssessmentType::find($id)->delete();

        return redirect()->back()->with('success', 'Assessment type deleted successfully.');
    }
}
