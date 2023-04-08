<?php

namespace App\Http\Controllers;

use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchoolPeriodController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'school_periods' => 'required|array',
            'school_periods.*.no_of_periods' => 'required|integer|gt:0',
            'school_periods.*.minutes_per_period' => 'required|integer|gt:0',
            'school_periods.*.start_time' => 'required|date_format:H:i',
            'school_periods.*.custom_periods' => 'nullable|array',
            'school_periods.*.custom_periods.*.name' => 'required|string',
            'school_periods.*.custom_periods.*.before_period' => 'required|integer|gt:0',
            'school_periods.*.custom_periods.*.duration' => 'required|integer',
            'school_periods.*.level_category_ids' => 'required|array',
            'school_periods.*.level_category_ids.*' => 'required|integer|distinct:strict|exists:level_categories,id',
        ]);

        $activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;

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
            $periods[] = [
                'name' => $i,
                'start_time' => $start_time->format('H:i'),
                'duration' => $minutes_per_period,
                'is_custom' => false,
                'school_year_id' => $activeSchoolYearId,
                'level_category_id' => $level_category_id,
            ];
            $start_time->addMinutes($minutes_per_period);

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
        }

        SchoolPeriod::insert($periods);
    }
}
