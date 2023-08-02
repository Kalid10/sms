<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueForUser implements ValidationRule
{
    private int $userId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('users')
            ->where($attribute, $value)
            ->where('id', '!=', $this->userId)
            ->exists();

        if ($exists) {
            $fail('The :attribute has already been taken.');
        }
    }
}
