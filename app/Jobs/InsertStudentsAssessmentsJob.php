<?php

namespace App\Jobs;

use App\Helpers\StudentGradeHelper;
use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InsertStudentsAssessmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $student_points;

    public Assessment $assessment;

    /**
     * Create a new job instance.
     */
    public function __construct(array $student_points, Assessment $assessment)
    {
        $this->student_points = $student_points;
        $this->assessment = $assessment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        StudentGradeHelper::processGrades($this->student_points, $this->assessment);
    }
}
