<?php

namespace App\Http\Middleware;

use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolYearStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, bool $isReady): Response
    {
        $activeSchoolYear = SchoolYear::getActiveSchoolYear();

        if ($isReady) {
            if ($activeSchoolYear === null || ! $activeSchoolYear?->is_ready) {

                return redirect()->to('/getting-started');
            }
        }

        if (! $isReady) {
            if ($activeSchoolYear !== null && $activeSchoolYear?->is_ready) {
                return redirect()->to('/admin');
            }
            redirect()->to('/getting-started');
        }

        return $next($request);
    }
}
