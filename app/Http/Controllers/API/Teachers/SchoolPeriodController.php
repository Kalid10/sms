<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\Request;
use App\Http\Resources\Teachers\SchoolPeriodCollection;
use App\Http\Resources\Teachers\SchoolPeriodResource;
use App\Models\LevelCategory;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;

class SchoolPeriodController extends Controller
{
    public function index(Request $request, ?SchoolPeriod $schoolPeriod)
    {
        if ($schoolPeriod->exists) {
            return new SchoolPeriodResource($schoolPeriod->load('schoolYear'));
        }

        return new SchoolPeriodCollection(SchoolPeriod::with('schoolYear')->where([
            'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
            'level_category_id' => LevelCategory::first()->id,
        ])->get());
    }
}
