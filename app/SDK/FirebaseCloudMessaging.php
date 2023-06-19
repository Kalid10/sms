<?php

namespace App\SDK;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseCloudMessaging
{
    public static function addUserToken(User $user, string $token): void
    {
        try {
            $currentTokens = collect($user->fcm_tokens) ?? collect();
            $currentTokens = $currentTokens->add($token)->unique()->toArray();

            $user->update([
                'fcm_tokens' => $currentTokens,
            ]);
        } catch (Exception $exception) {
            Log::info('Error during addToken: '.$exception);
        }
    }

    public static function removeUserToken(User $user, string $token): void
    {
        try {
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
        } catch (Exception $exception) {
            Log::info('Error during removeToken: '.$exception);
        }
    }
}
