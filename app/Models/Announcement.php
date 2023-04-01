<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'expires_on',
        'target_group',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    protected $casts = [
        'target_group' => 'array',
    ];
}
