<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $daysBefore;

    public function __construct($event, $daysBefore)
    {
        $this->event = $event;
        $this->daysBefore = $daysBefore;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        \Log::info('Sending event reminder', [
            'type' => 'New Event Reminder',
            'title' => 'Event Reminder',
            'body' => "The event '{$this->event->title}' is happening in {$this->daysBefore} day(s).",
            'url' => route('volunteer.eventDetails', $this->event->id),
            'user_id' => $notifiable->memberCredentialsID,
            'user_type' => get_class($notifiable),
        ]);
        return [
            'type' => 'New Event Reminder',
            'title' => 'Event Reminder',
            'body' => "The event '{$this->event->title}' is happening in {$this->daysBefore} day(s).",
            'url' => route('volunteer.eventDetails', $this->event->id),
            'user_id' => $notifiable->memberCredentialsID,
            'user_type' => get_class($notifiable),
            'is_read' => false,
        ];
    }
}
