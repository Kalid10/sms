<?php

namespace App\Http\Controllers\Auth;

use App\Facades\FirebaseCloudMessaging;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request): JsonResponse|Response|RedirectResponse
    {
        // Authenticate user
        $request->authenticate();

        // Handle request from InertiaJS
        if ($request->header('X-Inertia')) {
            // Regenerate request session
            $request->session()->regenerate();

            return match (auth()->user()->type) {
                User::TYPE_ADMIN => redirect()->route('admin.show'),
                User::TYPE_TEACHER => redirect()->route('teacher.show'),
                default => redirect()->route('login'),
            };
        }

        // Handle API request
        // Create token for user
        $token = auth()->user()->createToken(auth()->user()->name);

        // Add FCM token
        if ($request->has('fcmToken')) {
            FirebaseCloudMessaging::addUserToken(auth()->user(), $request->input('fcmToken'));
        }

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

            return redirect()->route('login.show');
        }

        // Revoke all tokens
        auth()->user()->tokens()->delete();

        // Revoke FCM token
        if ($request->has('fcmToken')) {
            FirebaseCloudMessaging::removeUserToken(auth()->user(), $request->input('fcmToken'));
        }

        // Return response from API
        return response()->json([
            'message' => 'You have successfully logged out.',
        ]);
    }

    public function firstLogin(): Response
    {
        return Inertia::render('Auth/Signup');
    }

    public function signup(SignupRequest $request): RedirectResponse
    {
        // Authenticate user
        $request->authenticate();

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'You have successfully logged in');
    }

    private function addFcmToken($user, $token): void
    {
        $currentTokens = collect($user->fcm_tokens) ?? collect([]);
        $currentTokens = $currentTokens->add($token)->unique()->toArray();

        $user->update([
            'fcm_tokens' => $currentTokens,
        ]);
    }

    private function removeFcmToken($user, $token): void
    {
        $currentTokens = collect($user->fcm_tokens);
        if ($currentTokens->count() === 0) {
            return;
        }

        $currentTokens = $currentTokens->filter(function ($item) use ($token) {
            return $item !== $token;
        })->toArray();

        $user->update([
            'fcm_tokens' => $currentTokens,
        ]);
    }
}
