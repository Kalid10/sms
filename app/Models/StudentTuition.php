<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTuition extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_UNPAID = 'unpaid';

    const STATUS_PAID = 'paid';

    protected $fillable = [
        'student_id',
        'fee_id',
        'status',
        'amount',
        'paid_at',
    ];

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class);
    }
}
