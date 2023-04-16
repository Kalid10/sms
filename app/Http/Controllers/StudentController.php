<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index()
    {
    }

    public function show(): Response
    {
        return Inertia::render('Students/Single');
    }
}
