<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperAINote
 */
class AINote extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'ai_notes';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonPlan(): BelongsTo
    {
        return $this->belongsTo(LessonPlan::class);
    }

    public function batchSession(): BelongsTo
    {
        return $this->belongsTo(BatchSession::class);
    }
}
