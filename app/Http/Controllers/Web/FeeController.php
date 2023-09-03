<?php

namespace App\Http\Controllers\Web;

use App\Models\Fee;
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
    public function index(Request $request): Response
    {

        $feeable_type = $request->input('feeable_type');
        $target_user_type = $request->input('target_user_type');

        $activeSemesters = Semester::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get();
        $activeQuarters = Quarter::whereIn('semester_id', $activeSemesters->pluck('id'))->with('semester')->get();

        $fees = Fee::when($feeable_type, function ($query, $feeable_type) {
            return $query->where('feeable_type', $feeable_type);
        })->when($target_user_type, function ($query, $target_user_type) {
            return $query->where('target_user_type', $target_user_type);
        })->get();

        return Inertia::render('Admin/Fees/Index', [
            'payment_providers' => PaymentProvider::all(),
            'fees' => $fees,
            'penalties' => Penalty::all(),
            'active_semesters' => $activeSemesters,
            'active_quarters' => $activeQuarters,
            'active_school_year_id' => SchoolYear::getActiveSchoolYear()->id,
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
        ]);

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
        ]);

        return redirect()->back()->with('success', 'Fee created successfully!');
    }
}
