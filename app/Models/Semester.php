<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';

    const STATUS_COMPLETED = 'completed';

    const STATUS_UPCOMING = 'upcoming';

    protected $fillable = [
        'name',
        'status',
        'start_date',
        'end_date',
        'school_year_id',
    ];
}
