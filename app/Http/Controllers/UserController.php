<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // Get users
        $users = User::select('id', 'name', 'email', 'type')->where('name', 'like', '%'.$searchKey.'%')->paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function createStudent(): \Inertia\Response
    {
        return Inertia::render('Users/Create/Student');
    }

    public function createTeacher(): \Inertia\Response
    {
        return Inertia::render('Users/Create/Teacher');
    }

    public function createGuardian(): \Inertia\Response
    {
        return Inertia::render('Users/Create/Guardian');
    }
}
