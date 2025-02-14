<?php

namespace App\Http\Controllers\API\Teachers;

use App\Models\Teacher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends \App\Http\Controllers\API\Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected static function teacher(): Teacher
    {
        return auth()->user()->load('teacher')->teacher;
    }
}
