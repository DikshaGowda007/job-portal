<?php

use App\Models\JobApplication;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/**
 * Private channel for a single conversation (job application thread).
 *
 * Channel name: conversation.{applicationId}
 *
 * Only the job seeker who applied OR the recruiter who owns the job post
 * may subscribe. Everyone else is rejected (returns false).
 */
Broadcast::channel('conversation.{applicationId}', function ($user, int $applicationId) {
    $application = JobApplication::with('jobPost')
        ->where('id', $applicationId)
        ->where('is_deleted', 0)
        ->first();

    if (! $application) {
        return false;
    }

    $isSeeker = $user->id === $application->user_id;
    $isRecruiter = $user->id === $application->jobPost->user_id;

    return $isSeeker || $isRecruiter;
});

/**
 * Presence channel for the same conversation.
 * Returns user data (not just true) so Echo can expose who is online.
 * The frontend uses this to show an online/offline indicator.
 */
Broadcast::channel('presence-conversation.{applicationId}', function ($user, int $applicationId) {
    $application = JobApplication::with('jobPost')
        ->where('id', $applicationId)
        ->where('is_deleted', 0)
        ->first();

    if (! $application) {
        return false;
    }

    $isSeeker = $user->id === $application->user_id;
    $isRecruiter = $user->id === $application->jobPost->user_id;

    if (! $isSeeker && ! $isRecruiter) {
        return false;
    }

    // Returning an array (not just true) makes this a presence channel.
    // This data is shared with all other members in the channel.
    return [
        'id' => $user->id,
        'name' => trim($user->first_name.' '.$user->last_name),
    ];
});
