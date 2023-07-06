<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $this->getUser(),
            ],
            'user_roles' => auth()->user() ? auth()->user()->roles()->get() : null,
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'info' => $request->session()->get('info'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ]);
    }

    private function getUser()
    {
        if (auth()->check()) {
            $user = auth()->user();

            $userTypeKey = match ($user->type) {
                User::TYPE_TEACHER => 'teacher',
                User::TYPE_STUDENT => 'student',
                default => null,
            };

            if ($userTypeKey) {
                $user->setAttribute($userTypeKey, $user->{$userTypeKey});
            }

            return $user;
        }

        return null;
    }
}
