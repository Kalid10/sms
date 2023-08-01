<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$userTypes)
    {
        // If the current user is not authenticated, return with
        // '401 Unauthorized' error.
        if (! auth()->check()) {
            // Handle request from InertiaJS
            if ($request->header('X-Inertia')) {
                abort(401, 'Access Denied!');
            }

            return response([
                'message' => 'You are not authenticated.',
            ], 401);
        }

        // Get the currently authenticated user
        $user = auth()->user();

        // Check the user type
        if (! in_array($user->type, $userTypes)) {
            // Handle request from InertiaJS
            if ($request->header('X-Inertia')) {
                abort(403, 'Access Denied!');
            }

            return response([
                'message' => 'Access Denied',
                'detailedMessage' => 'Access Denied. You are forbidden from accessing this resource or performing this operation.',
            ], 403);
        }

        return $next($request);
    }
}
