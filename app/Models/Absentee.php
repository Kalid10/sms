<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAbsentee
 */
class Absentee extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_session_id',
        'user_id',
        'reason',
        'next_class_attended_flag',
    ];

    public function batchSession(): BelongsTo
    {
        return $this->belongsTo(BatchSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
