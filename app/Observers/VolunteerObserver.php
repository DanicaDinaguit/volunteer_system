<?php

namespace App\Observers;

use App\Models\MemberCredential;
use App\Models\MessageThread;

class VolunteerObserver
{
    /**
     * Handle the MemberCredential "created" event.
     */
    public function created(MemberCredential $volunteer)
    {
        $groupChat = MessageThread::where('is_group_chat', true)->first();
        if ($groupChat) {
            $groupChat->participants()->attach($volunteer->id, ['participant_type' => 'App\Models\MemberCredential']);
        }
    }

    /**
     * Handle the MemberCredential "updated" event.
     */
    public function updated(MemberCredential $memberCredential): void
    {
        //
    }

    /**
     * Handle the MemberCredential "deleted" event.
     */
    public function deleted(MemberCredential $memberCredential): void
    {
        //
    }

    /**
     * Handle the MemberCredential "restored" event.
     */
    public function restored(MemberCredential $memberCredential): void
    {
        //
    }

    /**
     * Handle the MemberCredential "force deleted" event.
     */
    public function forceDeleted(MemberCredential $memberCredential): void
    {
        //
    }
}
