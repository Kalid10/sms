<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperBatchScheduleConfig
 */
class BatchScheduleConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_year_id',
        'max_periods_per_day',
        'max_periods_per_week',
    ];
}
