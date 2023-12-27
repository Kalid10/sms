<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Students\EventRequest;
use App\Http\Resources\Teachers\EventCollection;
use App\Models\Announcement;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index(EventRequest $eventRequest): EventCollection|JsonResponse
    {
        try {
            // Get active school year
            $activeSchoolYear = SchoolYear::getActiveSchoolYear();

            // Get SchoolSchedule events
            $schoolSchedule = SchoolSchedule::where('school_year_id', $activeSchoolYear->id)
                ->when($eventRequest->has('range'), function ($query) use ($eventRequest) {
                    if ($eventRequest->input('range') === 'month') {
                        $date = Carbon::createFromDate($eventRequest->input('date'));
                        $query
                            ->whereMonth('start_date', $date->month)
                            ->whereYear('start_date', $date->year);
                    } elseif ($eventRequest->input('range') === 'day') {
                        $query->whereDate('start_date', $eventRequest->input('date'));
                    }
                }, function ($query) {
                    $query->where('start_date', '>=', today()->toDateString());
                })
                ->get();

            Log::info('School Schedule: '.$schoolSchedule);

            $teacher = Auth::user();

            $activeQuarter = Quarter::getActiveQuarter();

            $assessments = $teacher->load([
                'teacher.batchSubjects.batch.level',
                'teacher.batchSubjects.subject',
                'teacher.batchSubjects.teacher.user',
                'teacher.batchSubjects.assessments' => function ($query) use ($eventRequest, $activeQuarter) {
                    $query->where('quarter_id', $activeQuarter->id)
                        ->when($eventRequest->has('range'), function ($query) use ($eventRequest) {
                            if ($eventRequest->input('range') === 'month') {
                                $date = Carbon::createFromDate($eventRequest->input('date'));
                                $query
                                    ->whereMonth('due_date', $date->month)
                                    ->whereYear('due_date', $date->year);
                            } elseif ($eventRequest->input('range') === 'day') {
                                $query->whereDate('due_date', $eventRequest->input('date'));
                            }
                        }, function ($query) {
                            $query->whereDate('due_date', today()->toDateString());
                        });
                },
            ])->teacher->batchSubjects->pluck('assessments')->flatten();

            Log::info('Assessment: '.$assessments);

            // Get announcements with "teachers" target group based on the active school year id and range (month or day) and date and use expires_on for date
            $announcements = Announcement::whereJsonContains('target_group', 'teachers')
                ->where('school_year_id', $activeSchoolYear->id)
                ->when($eventRequest->has('range'), function ($query) use ($eventRequest) {
                    if ($eventRequest->input('range') === 'month') {
                        $date = Carbon::createFromDate($eventRequest->input('date'));
                        $query
                            ->whereMonth('expires_on', $date->month)
                            ->whereYear('expires_on', $date->year);
                    } elseif ($eventRequest->input('range') === 'day') {
                        $query->whereDate('expires_on', $eventRequest->input('date'));
                    }
                }, function ($query) {
                    $query->whereDate('expires_on', today()->toDateString());
                })
                ->with('author.user')
                ->get();

            Log::info('Anno: '.$announcements);

            $schedule = $schoolSchedule->concat($assessments)
                ->sortBy(function ($event) {
                    return $event->end_date ?? ($event->assessment ? $event->assessment->due_date : null);
                });

            // Concatenate $schedule and $announcements
            $events = $schedule->concat($announcements);

            return new EventCollection($events);

        } catch (Exception $e) {

            Log::info('In the catch: '.$e);

            // Handle exceptions (e.g., log the error, return an error response)
            return response()->json(['error' => 'An error occurred while fetching events.'], 500);
        }
    }
}
