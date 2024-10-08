<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;


class GoogleCalendarController extends Controller
{
    public function fetchEvents()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-calendar/service-account.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $service = new Google_Service_Calendar($client);
        $calendarId = '5f60ab0fe80f5fb52256c5858400e342e17127ac613eee2f37d19aed2bff487a@group.calendar.google.com';

        try {
            $events = $service->events->listEvents($calendarId);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events: ' . $e->getMessage()], 500);
        }

        $formattedEvents = [];
        foreach ($events->getItems() as $event) {
            $formattedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getSummary(),
                'event_start' => $event->getStart()->getDateTime() ?? $event->getStart()->getDate(),
                'event_end'   => $event->getEnd()->getDateTime() ?? $event->getEnd()->getDate(),
                'description' => $event->getDescription(),
            ];
        }

        return response()->json($formattedEvents);
    }
}
