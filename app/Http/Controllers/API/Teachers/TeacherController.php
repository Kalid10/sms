<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Controllers\API\Controller;
use App\Http\Resources\Teachers\Resource;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(): Resource
    {
        return new Resource(Auth::user()->load('address', 'teacher'));
    }
}
