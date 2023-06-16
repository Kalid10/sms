<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Event\Collection;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Student $student = null): Collection
    {
        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->where('start_date', '>=', today()->toDateString())
            ->get();

        if ($student) {
            $children = collect([$student->load('batches.batch.subjects.assessments')]);
        } else {
            $children = Auth::user()->load('guardian.children.batches.batch.subjects.assessments')->guardian->children;
        }

        // Get all assessments in one collection
        $assessments = $children->flatMap(function ($student) {
            return $student->batches->flatMap(function ($batch) {
                return $batch->batch->subjects->flatMap(function ($subject) {
                    return $subject->assessments->map(function ($assessment) {
                        $assessment->assessment_period_time = $assessment->getAssessmentPeriodTime();

                        return $assessment;
                    });
                });
            });
        });

        // Merge the collections
        $events = $assessments->concat($schoolSchedule);

        // Sort the collection
        $sorted = $events->sortBy(function ($event) {
            return $event->end_date ?? $event->due_date;
        });

        return new Collection($sorted->values());
    }
}
