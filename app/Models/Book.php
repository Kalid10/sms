<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'level_id',
        'subject_id',
        'tags',
        'cover',
        'published_at',
    ];

    public function chapters(): HasMany
    {
        return $this->hasMany(BookChapter::class);
    }
}
