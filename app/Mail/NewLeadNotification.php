<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New inquiry from '.$this->lead->name.' — fahad-jadiya.com',
            replyTo: [$this->lead->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.leads.new',
            with: ['lead' => $this->lead],
        );
    }
}
