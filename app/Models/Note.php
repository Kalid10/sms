<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'entity_id',
        'body',
        'title',
        'batch_session_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entity_id');
    }

    public function batchSession(): BelongsTo
    {
        return $this->belongsTo(BatchSession::class);
    }
}
