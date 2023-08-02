<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Event\Collection;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(?Student $student): Collection
    {
        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->where('start_date', '>=', today()->toDateString())
            ->get();

        if ($student->exists) {
            $assessments = $student->load('assessments.student.user', 'assessments.assessment')->assessments;
        } else {
            $assessments = Auth::user()
                ->load('guardian.children.assessments.student.user', 'guardian.children.assessments.assessment')
                ->guardian->children->pluck('assessments')->flatten();
        }

        $events = $schoolSchedule->concat($assessments)->sortBy(function ($event) {
            return $event->end_date ?? $event->assessment->due_date;
        });

        return new Collection($events);
    }
}
