<?php
namespace App\Services;

use App\Models\Suspension;
use App\Models\User;
use App\Notifications\UserSuspended;
use Carbon\Carbon;
use Exception;

class UserService {

    /**
     * @function suspend a user
     * @param string $userId
     * @param User $admin
     * @param int $duration
     * @param string $reason
     * @returns void
     */
    public function suspend (string $userId, User $admin, int $duration, string $reason)
    {
        $until = $duration === '99' ? Carbon::now()->addYears($duration) : Carbon::now()->addDays($duration);
        Suspension::create([
            'user_id' => $userId,
            'until' => $until,
            'issuer_id' => $admin->id,
            'issuer_reason' => $reason
        ]);
        // send notification to user.
        $suspendedUser = User::find($userId);
        $suspendedUser->notify(
            (new UserSuspended($duration, $until, $reason))
                ->locale($suspendedUser->locale)
        );
    }

}
