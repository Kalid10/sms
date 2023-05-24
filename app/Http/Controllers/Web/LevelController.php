<?php

namespace App\Http\Controllers\Web;

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

        return Inertia::render('Admin/Levels/Index', [
            'levels' => $levels,
        ]);
    }

    public function show(Level $level, Request $request): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // For paginated data, get page and perPage value
        $page = $request->input('page', 1);

        // For paginated data, get perPage value
        $perPage = $request->input('per_page', 10);

        // Get section filter
        $sectionFilter = $request->input('section');

        return Inertia::render('Levels/Single', [
            'level' => $level->load([
                'levelCategory',
                'batches.schoolYear',
                'batches.subjects.subject' => function ($query) {
                    $query->withTrashed('subjects');
                },
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
            'students' => Inertia::lazy(fn () => BatchStudent::whereIn('batch_id', $level->batches->pluck('id'))
                ->with(
                    'student:id,user_id',
                    'student.user:id,name,email,username,phone_number,gender,date_of_birth',
                    'batch:id,section')
                ->when($searchKey, function ($query) use ($searchKey) {
                    return $query->whereHas('student.user', function ($query) use ($searchKey) {
                        return $query->where('name', 'like', "%{$searchKey}%");
                    });
                })
                ->when($sectionFilter, function ($query) use ($sectionFilter) {
                    return $query->whereHas('batch', function ($query) use ($sectionFilter) {
                        return $query->where('section', $sectionFilter);
                    });
                })
                ->paginate($perPage, ['*'], 'page', $page)
                ->map(fn ($batchStudent) => [
                    ...$batchStudent->student->user->toArray(),
                    'student_id' => $batchStudent->student->id,
                    'section' => $batchStudent->batch->section,
                    'updated_at' => $batchStudent->updated_at->diffForHumans(Carbon::now()),
                ])
            ),
        ]);
    }
}
