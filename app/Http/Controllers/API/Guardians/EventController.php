<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Requests\API\Students\EventRequest;
use App\Http\Resources\Guardians\Event\Collection;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(EventRequest $request, ?Student $student): Collection
    {
        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->when($request->has('range'), function ($query) use ($request) {
                if ($request->input('range') === 'month') {
                    $date = Carbon::createFromDate($request->input('date'));
                    $query
                        ->whereMonth('start_date', $date->month)
                        ->whereYear('start_date', $date->year);
                } elseif ($request->input('range') === 'day') {
                    $query->whereDate('start_date', $request->input('date'));
                }
            }, function ($query) {
                $query->where('start_date', '>=', today()->toDateString());
            })
            ->get();

        if ($student->exists) {
            $assessments = $student
                ->load('assessments.student.user', 'assessments.assessment')
                ->assessments->where(function ($query) use ($request) {
                    $this->filterAssessments($query, $request);
                });
        } else {
            $assessments = Auth::user()
                ->load([
                    'guardian.children.assessments.student.user',
                    'guardian.children.assessments.assessment',
                    'guardian.children.assessments' => function ($query) use ($request) {
                        $this->filterAssessments($query, $request);
                    },
                ])
                ->guardian->children->pluck('assessments')->flatten();
        }

        $events = $schoolSchedule->concat($assessments)->sortBy(function ($event) {
            return $event->end_date ?? $event->assessment->due_date;
        });

        return new Collection($events);
    }

    private function filterAssessments($query, $request): void
    {
        $query->when($request->has('range'), function ($query) use ($request) {
            if ($request->input('range') === 'month') {
                $date = Carbon::createFromDate($request->input('date'));
                $query->whereHas('assessment', function ($query) use ($date) {
                    $query
                        ->whereYear('due_date', $date->year)
                        ->whereMonth('due_date', $date->month);
                });
            } elseif ($request->input('range') === 'day') {
                $query->whereHas('assessment', function ($query) use ($request) {
                    $query->whereDate('due_date', $request->input('date'));
                });
            }
        }, function ($query) {
            $query->whereHas('assessment', function ($query) {
                $query->whereDate('due_date', '>=', today()->toDateString());
            });
        });
    }
}
