<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPenalty
 */
class Penalty extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_FLAT_RATE = 'flat_rate';

    const TYPE_PERCENTAGE = 'percentage';

    const TYPE_DAILY = 'daily';

    protected $fillable = [
        'type',
        'amount',
    ];
}
