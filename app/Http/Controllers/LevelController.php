<?php

namespace App\Http\Controllers;

use App\Models\BatchStudent;
use App\Models\Level;
use App\Models\SchoolYear;
use Carbon\Carbon;
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

    public function show(Level $level, Request $request): Response
    {
        return Inertia::render('Levels/Single', [
            'level' => $level->load([
                'levelCategory',
                'batches.schoolYear',
                'batches.subjects.subject',
                'batches.subjects.teacher.user',
                'batches' => function ($query) {
                    $query
                        ->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
                        ->withCount('students', 'subjects');
                },
            ])
                ->loadCount([
                    'batches' => function ($query) {
                        $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                    },
                ])
                ->only('id', 'name', 'level_category_id', 'updated_at', 'batches', 'batches_count'),
            'batches' => $level->batches,
            'students' => Inertia::lazy(fn () => BatchStudent::whereIn('batch_id', Level::first()->batches->pluck('id'))
                    ->with(
                        'student:id,user_id',
                        'student.user:id,name,email,username,phone_number,gender,date_of_birth',
                        'batch:id,section')
                    ->get()
                ->map(fn ($batchStudent) => [
                    ...$batchStudent->student->user->toArray(),
                    'section' => $batchStudent->batch->section,
                    'updated_at' => $batchStudent->updated_at->diffForHumans(Carbon::now()),
                ])
            ),
        ]);
    }
}
