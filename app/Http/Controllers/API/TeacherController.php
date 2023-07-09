<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Teacher\Resource;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index(Teacher $teacher): Resource
    {
        return new Resource($teacher->load('user'));
    }
}
