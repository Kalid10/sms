<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class TeacherAssessmentService
{
    public static function analytics(Collection $studentAssessments): array
    {
        $passed = $studentAssessments->filter(function ($studentAssessment) use ($studentAssessments) {
            return $studentAssessment->point >= $studentAssessments->first()->assessment->maximum_point / 2;
        });

        $failed = $studentAssessments->filter(function ($studentAssessment) use ($studentAssessments) {
            return $studentAssessment->point < $studentAssessments->first()->assessment->maximum_point / 2;
        });

        $assessmentAnalytics = [

            // calculate the average score
            'average_score' => $studentAssessments->avg('point'),

            // calculate the highest score
            'top_score' => $studentAssessments->max('point'),

            // calculate the lowest score
            'lowest_score' => $studentAssessments->min('point'),

            'passed_count' => $passed->count(),

            // calculate passed students percentage
            'passed_percentage' => $passed->count() / $studentAssessments->count() * 100,

            'failed_count' => $failed->count(),

            // calculate failed students percentage
            'failed_percentage' => $failed->count() / $studentAssessments->count() * 100,

            // calculate above average students percentage
            'above_average_percentage' => $studentAssessments->filter(function ($studentAssessment) use ($studentAssessments) {
                return $studentAssessment->point > $studentAssessments->avg('point');
            })->count() / $studentAssessments->count() * 100,
        ];

        return $assessmentAnalytics;
    }
}
