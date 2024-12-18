<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        // Combine first, middle, and last names into a single name
        $this->data['name'] = "{$data['first_name']} {$data['middle_name']} {$data['last_name']}";
    }

    public function build()
    {
        return $this->markdown('emails.application-submitted')
                    ->with([
                        'name' => $this->data['name'],
                    ]);
    }
}