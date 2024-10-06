<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\MessageThread;

class AdminObserver
{
    /**
     * Handle the Admin "created" event.
     */
    public function created(Admin $admin)
    {
        $groupChat = MessageThread::where('is_group_chat', true)->first();
        if ($groupChat) {
            $groupChat->participants()->attach($admin->AdminId, ['participant_type' => 'App\Models\Admin']);
        }
    }

    /**
     * Handle the Admin "updated" event.
     */
    public function updated(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "deleted" event.
     */
    public function deleted(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "restored" event.
     */
    public function restored(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "force deleted" event.
     */
    public function forceDeleted(Admin $admin): void
    {
        //
    }
}
