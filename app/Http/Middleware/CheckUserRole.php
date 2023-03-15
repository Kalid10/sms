<?php

namespace App\Http\Middleware;

use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userRole): Response
    {
        // If the current user is not authenticated, return with
        // '401 Unauthorized' error.
        if (! auth()->check()) {
            // Handle request from InertiaJS
            if ($request->header('X-Inertia')) {
                abort(401, 'Access Denied!');
            }

            return response([
                'message' => 'You are not authenticated for roles.',
            ], 401);
        }

        // Get the currently authenticated user
        $user = auth()->user();

        // If the user doesn't have the required role, return with
        // '403 Forbidden' error.
        if (! UserRole::where('role_name', $userRole)->where('user_id', $user->id)->exists()) {
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
