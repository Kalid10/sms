<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherFeedback extends Model
{
    use HasFactory;

    protected $table = 'teachers_feedback';

    protected $fillable = [
        'teacher_id',
        'author_id',
        'feedback',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
