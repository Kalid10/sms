<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all  the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('students-import', function ($user) {
    return $user->hasRole('manage-students');
});

Broadcast::channel('teachers-import', function ($user) {
    return $user->hasRole('manage-teachers');
});

Broadcast::channel('batch-schedule', function ($user) {
    return $user->hasRole('manage-batch-schedules');
});

Broadcast::channel('mark-assessment', function ($user) {
    return $user->type === User::TYPE_TEACHER;
});

Broadcast::channel('question-generator', function ($user) {
    return $user->type === User::TYPE_TEACHER;
});

Broadcast::channel('mass-assessment', function ($user) {
    return $user->hasRole('manage-assessment-types');
});

Broadcast::channel('student-fee', function ($user) {
    return $user->hasRole('manage-finance');
});
