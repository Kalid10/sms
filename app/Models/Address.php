<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $fillable = [
        'sub_city',
        'city',
        'country',
        'woreda',
        'house_number',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
