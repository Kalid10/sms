<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Resources\Guardians\Term\TermCollection;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(Request $request): TermCollection
    {
        $request->validate([
            'type' => 'required|in:quarter,semester,school_year',
        ]);

        return new TermCollection(match ($request->input('type')) {
            'quarter' => Quarter::with('semester.schoolYear')->orderBy('start_date', 'desc')->get(),
            'semester' => Semester::with('schoolYear')->orderBy('start_date', 'desc')->get(),
            'school_year' => SchoolYear::orderBy('start_date', 'desc')->get()
        });
    }
}
