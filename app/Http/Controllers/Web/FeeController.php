<?php

namespace App\Http\Controllers\Web;

use App\Jobs\StudentFeeJob;
use App\Models\Fee;
use App\Models\LevelCategory;
use App\Models\PaymentProvider;
use App\Models\Penalty;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentTuition;
use App\Models\User;
use App\Services\StudentFeeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeeController extends Controller
{
    public function index(Request $request): Response
    {
        $searchKey = $request->input('find');

        $feeable_type = $request->input('feeable_type');
        $target_user_type = $request->input('target_user_type');

        $status = $request->input('status');

        $activeSemesters = Semester::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get();
        $activeQuarters = Quarter::whereIn('semester_id', $activeSemesters->pluck('id'))->with('semester')->get();

        $fees = Fee::with('penalty', 'levelCategory')
            ->when($feeable_type, function ($query, $feeable_type) {
                return $query->where('feeable_type', $feeable_type);
            })->when($target_user_type, function ($query, $target_user_type) {
                return $query->where('target_user_type', $target_user_type);
            })->get();

        $studentTuitions = StudentTuition::with(
            'fee',
            'student.user:id,name,gender,username',
            'student.guardian.user:id,name,phone_number,email',
            'paymentProvider'
        )->when($searchKey, function ($query, $searchKey) {
            return $query->whereHas('student.user', function ($query) use ($searchKey) {
                return $query->where('name', 'like', '%'.$searchKey.'%')
                    ->orWhere('username', 'like', '%'.$searchKey.'%');
            });
        })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(10);

        return Inertia::render('Admin/Finance/Index', [
            'payment_providers' => PaymentProvider::all(),
            'fees' => $fees,
            'penalties' => Penalty::all(),
            'active_semesters' => $activeSemesters,
            'active_quarters' => $activeQuarters,
            'active_school_year_id' => SchoolYear::getActiveSchoolYear()->id,
            'level_categories' => LevelCategory::all(),
            'student_tuitions' => $studentTuitions,
            'student_tuition_history' => Inertia::lazy(function () use ($request) {

                if ($request->input('student_id')) {
                    $student = Student::find($request->input('student_id'))->load('user');

                    return StudentTuition::with('fee', 'student.user:id,name,gender,username', 'paymentProvider')
                        ->where('student_id', $student->id)
                        ->get();
                }
            }),
        ]);
    }

    public function create(Request $request, StudentFeeService $studentFeeService): RedirectResponse
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
            'is_student_tuition_fee' => 'required|boolean',
        ]);

        match ($request->feeable_type) {
            'semesters' => $request->merge(['feeable_type' => Semester::class]),
            'quarters' => $request->merge(['feeable_type' => Quarter::class]),
            'school_years' => $request->merge(['feeable_type' => SchoolYear::class]),
        };

        $fees = collect();

        foreach ($request->level_category_ids as $level_category_id) {
            $fee = Fee::create([
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
                'is_student_tuition_fee' => $request->is_student_tuition_fee,

            ]);

            $fees = $fees->push($fee);
        }

        StudentFeeJob::dispatch($request->all(), $fees);

        return redirect()->back()->with('success', 'Fee is being created, we will notify you once we are done!');
    }
}
