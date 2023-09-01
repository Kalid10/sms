<?php

namespace App\Http\Controllers\Web;

use App\Models\Fee;
use App\Models\LevelCategory;
use App\Models\PaymentProvider;
use App\Models\Penalty;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeeController extends Controller
{
    public function index(): Response
    {
        $activeSemesters = Semester::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get();
        $activeQuarters = Quarter::whereIn('semester_id', $activeSemesters->pluck('id'))->with('semester')->get();

        return Inertia::render('Admin/Finance/Index', [
            'payment_providers' => PaymentProvider::all(),
            'fees' => Fee::with('penalty', 'levelCategory')->get(),
            'penalties' => Penalty::all(),
            'active_semesters' => $activeSemesters,
            'active_quarters' => $activeQuarters,
            'active_school_year_id' => SchoolYear::getActiveSchoolYear()->id,
            'level_categories' => LevelCategory::all(),
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:fees,name',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'penalty_id' => 'nullable|exists:penalties,id',
            'feeable_type' => 'required|in:semesters,quarters,school_years',
            'feeable_id' => 'required|exists:'.$request->feeable_type.',id',
            'is_active' => 'required|boolean',
            'due_date' => 'date|after_or_equal:today',
            'level_category_ids' => 'required|array|min:1',
            'level_category_ids.*' => 'required|exists:level_categories,id',
        ]);

        match ($request->feeable_type) {
            'semesters' => $request->merge(['feeable_type' => Semester::class]),
            'quarters' => $request->merge(['feeable_type' => Quarter::class]),
            'school_years' => $request->merge(['feeable_type' => SchoolYear::class]),
        };

        foreach ($request->level_category_ids as $level_category_id) {
            Fee::create([
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'penalty_id' => $request->penalty_id,
                'feeable_type' => $request->feeable_type,
                'feeable_id' => $request->feeable_id,
                'target_user_type' => User::TYPE_STUDENT,
                'status' => $request->is_active ? Fee::STATUS_ACTIVE : Fee::STATUS_INACTIVE,
                'due_date' => Carbon::parse($request->due_date),
                'level_category_id' => $level_category_id,
            ]);
        }

        return redirect()->back()->with('success', 'Fee created successfully!');
    }
}
