<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryCheckInOut extends Model
{
    use HasFactory, SoftDeletes;

    const STATE_CHECKED_OUT = 'checked_out';

    const STATE_CHECKED_IN = 'checked_in';

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_DECLINED = 'declined';

    protected $guarded = [];
}
