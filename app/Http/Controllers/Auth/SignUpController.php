<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class SignUpController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/SignUp');
    }

    public function signUp(SignUpRequest $request): RedirectResponse
    {
        // Authenticate user
        $request->authenticate();

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'You have successfully logged in');
    }
}
