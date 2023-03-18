<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        // Authenticate user
        $request->authenticate();

        // Handle request from InertiaJS
        if ($request->header('X-Inertia')) {
            // Regenerate request session
            $request->session()->regenerate();

            // Todo: Change page
            return Inertia::render('Welcome');
        }

        // Handle API request
        // Create token for user
        $token = auth()->user()->createToken(auth()->user()->name);

        // Return token with user
        return response()
            ->json([
                'message' => 'You have successfully logged in.',
                'user' => auth()->user(),
                'token' => $token->plainTextToken,
            ]);
    }
}
