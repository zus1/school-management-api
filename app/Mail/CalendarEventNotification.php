<?php

namespace App\Mail;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CalendarEventNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private Event $event
    ){
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                address: config('mail.from.no_reply_address'),
                name: config('mail.from.name'),
            ),
            subject: config('calendar-event.notification.before_start'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'calendar.event-notification-txt',
            markdown: 'calendar.event-notification',
            with: [
                'name' => $this->event->title,
                'startsAt' => $this->event->starts_at,
                'startsIn' => ceil(Carbon::now()->diffInMinutes(new Carbon($this->event->starts_at)))
            ],
        );
    }
}
