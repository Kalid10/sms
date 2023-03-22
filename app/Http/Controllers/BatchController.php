<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batches\CreateBulkRequest;
use App\Http\Requests\Batches\CreateRequest;
use App\Models\Batch;
use Inertia\Inertia;

class BatchController extends Controller
{
    // Create batch for school year with section for levels
    public function create(CreateRequest $request)
    {
        // Get the validated data
        $validated = $request->validated();

        // Create batch
        Batch::create($validated);

        // TODO: Change the component name to the correct one
        return Inertia::render('Welcome', [
            'batches' => Batch::with('level', 'schoolYear')->get(),
        ]);
    }

    public function createBulk(CreateBulkRequest $request)
    {
        // Get the validated data
        $validated = $request->validated();

        $schoolYearId = $validated['batches']['school_year_id'];

        foreach ($validated['batches']['grade'] as $batch) {
            $levelId = $batch['level_id'];
            $sections = $batch['sections'];

            foreach ($sections as $section) {
                Batch::create([
                    'level_id' => $levelId,
                    'school_year_id' => $schoolYearId,
                    'section' => $section,
                ]);
            }
        }

        return Inertia::render('Welcome', [
            'batches' => Batch::with('level', 'schoolYear')->get(),
        ]);
    }
}
