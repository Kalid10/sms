<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssessmentsGrade extends Model
{
    use HasFactory;

    protected $table = 'student_assessments_grades';

    protected $guarded = [
        'updated_at',
        'created_at',
    ];
}
