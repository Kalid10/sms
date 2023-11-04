<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStudentNote
 */
class StudentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'student_id',
        'author_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
