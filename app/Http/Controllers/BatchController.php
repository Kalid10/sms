<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batches\CreateBulkRequest;
use App\Http\Requests\Batches\CreateRequest;
use App\Models\Batch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BatchController extends Controller
{
    public function create(CreateRequest $request): Response
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

    public function createBulk(CreateBulkRequest $request): Response
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

    // Add get batches for school year
    public function list(Request $request): Response
    {
        $schoolYearId = $request->input('school_year_id');

        if (! $schoolYearId) {
            $batches = Batch::with('level', 'schoolYear')->get();

            return Inertia::render('Welcome', [
                'batches' => $batches,
            ]);
        }
        $batches = Batch::with('level', 'schoolYear')
            ->where('school_year_id', $schoolYearId)
            ->get();

        return Inertia::render('Welcome', [
            'batches' => $batches,
        ]);
    }
}
