<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request): JsonResponse|Response
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

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        // Handle request from InertiaJS
        if ($request->header('X-Inertia')) {
            // Logout user
            auth('web')->logout();

            // Regenerate session
            $request->session()->invalidate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            return redirect()->back()->with('success', 'You have successfully logged out.');
        }

        // Revoke all tokens
        auth()->user()->tokens()->delete();

        // Return response from API
        return response()->json([
            'message' => 'You have successfully logged out.',
        ]);
    }
}
