<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Tuition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TuitionReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private Student $student,
        private Tuition $tuition,
    ){
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unpaid Tuition',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'tuition.reminder-txt',
            markdown: 'tuition.reminder',
            with: [
                'studentFirstName' => $this->student->first_name,
                'studentLastName' => $this->student->last_name,
                'dueAt' => $this->tuition->due_at
            ]
        );
    }
}
