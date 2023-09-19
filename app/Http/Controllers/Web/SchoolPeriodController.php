<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\SchoolPeriods\CreateRequest;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use Carbon\Carbon;

class SchoolPeriodController extends Controller
{
    public function create(CreateRequest $request)
    {
        $activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;
        $activeSchoolYearPeriods = SchoolPeriod::where('school_year_id', $activeSchoolYearId);

        // Check if there is active school year
        if (! $activeSchoolYearId) {
            return redirect()->back()->with('error', 'There is no active school year');
        }

        // Check if there are any school periods for the active school year
        if ($activeSchoolYearPeriods->exists()) {
            $activeSchoolYearPeriods->delete();
        }

        // Loop through the school periods array
        foreach ($request->school_periods as $schoolPeriodData) {
            $start_time = Carbon::createFromFormat('H:i', $schoolPeriodData['start_time']);
            $minutes_per_period = $schoolPeriodData['minutes_per_period'];
            $no_of_periods = $schoolPeriodData['no_of_periods'];
            $custom_periods = collect($schoolPeriodData['custom_periods'])->keyBy('before_period');
            $level_categories_ids = $schoolPeriodData['level_category_ids'];

            // Loop through the level categories and create the school periods
            foreach ($level_categories_ids as $level_category_id) {
                $this->createSchoolPeriods($start_time, $minutes_per_period, $no_of_periods, $custom_periods, $activeSchoolYearId, $level_category_id);
            }
        }

        return redirect()->back()->with('success', 'School periods created successfully');
    }

    private function createSchoolPeriods($start_time, $minutes_per_period, $no_of_periods, $custom_periods, $activeSchoolYearId, $level_category_id)
    {
        $periods = [];

        for ($i = 1; $i <= $no_of_periods; $i++) {
            // Check if there is a custom period after the current period
            if ($custom_periods->has($i)) {
                $custom_period = $custom_periods->get($i);

                $periods[] = [
                    'name' => $custom_period['name'],
                    'start_time' => $start_time->format('H:i'),
                    'duration' => $custom_period['duration'],
                    'is_custom' => true,
                    'school_year_id' => $activeSchoolYearId,
                    'level_category_id' => $level_category_id,
                ];
                $start_time->addMinutes($custom_period['duration']);
            }

            $periods[] = [
                'name' => $i,
                'start_time' => $start_time->format('H:i'),
                'duration' => $minutes_per_period,
                'is_custom' => false,
                'school_year_id' => $activeSchoolYearId,
                'level_category_id' => $level_category_id,
            ];
            $start_time->addMinutes($minutes_per_period);
        }

        SchoolPeriod::insert($periods);
    }
}
