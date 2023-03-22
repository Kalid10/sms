<?php

namespace App\Http\Controllers;

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
}
