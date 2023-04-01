<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batches\CreateBulkRequest;
use App\Http\Requests\Batches\CreateRequest;
use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BatchController extends Controller
{
    public function create(CreateRequest $request): Response|RedirectResponse
    {
        $validated = $request->validated();

        // Get active school year
        $schoolYear = SchoolYear::getActiveSchoolYear();

        if (! $schoolYear) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }

        Batch::create(array_merge(
            $validated, ['school_year_id' => $schoolYear->id]
        ));

        return redirect()->back()->with('success', 'Batch created successfully!');
    }

    public function createBulk(CreateBulkRequest $request): RedirectResponse|Response
    {
        $validated = $request->validated();

        $schoolYearId = SchoolYear::getActiveSchoolYear()->id;

        if (! $schoolYearId) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }

        DB::beginTransaction();
        try {
            foreach ($validated['batches'] as $batch) {
                $levelId = $batch['level_id'];

                if (is_array($batch['level_id'])) {
                    $levelId = Level::create([
                        'name' => $batch['level_id']['name'],
                    ])->id;
                }

                $noOfSections = $batch['no_of_sections'];

                if (Batch::where('level_id', $levelId)
                        ->where('school_year_id', $schoolYearId)->count() === $noOfSections) {
                    continue;
                }

                for ($i = 0; $i < $noOfSections; $i++) {
                    $section = chr(65 + $i);
                    Batch::create([
                        'level_id' => $levelId,
                        'school_year_id' => $schoolYearId,
                        'section' => $section,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->withErrors(['batches' => 'Something went wrong!']);
        }

        return redirect()->back()->with('success', 'Batches created successfully!');
    }

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

    public function active(): RedirectResponse|Response
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();

        if (! $schoolYear) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }
        $batches = Batch::with('level', 'schoolYear')
            ->where('school_year_id', $schoolYear->id)
            ->get();

        return Inertia::render('Welcome', [
            'batches' => $batches,
        ]);
    }
}
