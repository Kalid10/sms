<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelController extends Controller
{
    public function create(Request $request)
    {
        // Overwrite the default error message
        $messages = [
            'name.unique' => 'Grade already exists',
        ];

        $request->validate([
            'name' => 'required|string|unique:levels,name',
        ], $messages);

        Level::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Level created successfully');
    }

    public function list(): Response
    {
        $levels = Level::with([
            'levelCategory',
            'batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            },
        ])->get();

        return Inertia::render('Levels/Index', [
            'levels' => $levels,
        ]);
    }

    public function detail(Level $level): Response
    {
        $levelDetail = $level->load(['levelCategory', 'batches.students.student', 'batches.schoolYear', 'batches.subjects', 'batches' => function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
        },
        ]);

        return Inertia::render('Levels/Single', [
            'level_details' => $levelDetail,
        ]);
    }
}
