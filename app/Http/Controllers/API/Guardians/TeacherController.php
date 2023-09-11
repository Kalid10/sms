<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Resources\Guardians\Teacher\Resource;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index(Teacher $teacher): Resource
    {
        return new Resource($teacher->load('user'));
    }
}
