<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\MemberCredential;
use App\Models\Participant;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminders for events happening in 3 days and 1 day';

    public function handle()
{
    // Log the start of the reminder task
    Log::info('Sending event reminders...');
    $today = Carbon::today();

    // Check for events happening in 3 days
    $eventsInThreeDays = Event::whereDate('event_date', $today->copy()->addDays(3)->toDateString())->get();
    Log::info('Events in 3 days: ' . $eventsInThreeDays->count()); // Log the number of events found
    $this->sendNotifications($eventsInThreeDays, 3);

    // Check for events happening in 1 day
    $eventsInTwoDay = Event::whereDate('event_date', $today->copy()->addDays(2)->toDateString())->get();
    Log::info('Events in 2 days: ' . $eventsInTwoDay->count()); // Log the number of events found
    $this->sendNotifications($eventsInTwoDay, 1);

    // Check for events happening in 1 day
    $eventsInOneDay = Event::whereDate('event_date', $today->copy()->addDays(1)->toDateString())->get();
    Log::info('Events in 1 day: ' . $eventsInOneDay->count()); // Log the number of events found
    $this->sendNotifications($eventsInOneDay, 1);

    $this->info('Reminders sent successfully.');
}


    protected function sendNotifications($events, $daysBefore)
    {
        foreach ($events as $event) {
            // Retrieve participants of the event
            $participants = Participant::where('eventID', $event->id)->get();

            // Loop through each participant and send a notification
            foreach ($participants as $participant) {
                $volunteer = $participant->volunteer; // Access the volunteer (MemberCredential) model
                if ($volunteer) {
                    \App\Models\Notification::create([
                        'user_id' => $volunteer->memberCredentialsID,
                        'user_type' => get_class($volunteer),
                        'type' => 'New Event Reminder',
                        'title' => 'Event Reminder',
                        'body' => "The event '{$event->title}' is happening in {$daysBefore} day(s).",
                        'url' => route('volunteer.eventDetails', $event->id),
                        'is_read' => false,
                    ]);
                    // $volunteer->notify(new EventReminderNotification($event, $daysBefore));
                }
            }
        }
    }
}
