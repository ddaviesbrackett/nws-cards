<?php

namespace App\Mail;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public string $em;
    public string $nm;
    public string $msg;

    /**
     * Create a new message instance.
     */
    public function __construct(string $email, string $name, string $msg)
    {
        $this->em = $email;
        $this->nm = $name;
        $this->msg = $msg;
    }
    
    public function middleware(): array
    {
        return [new RateLimited('resend-emails')];
    }
    
    public function retryUntil(): DateTime
    {
        return now()->addHours(1);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Home Page contact request',
            to: ["nwsgrocerycards@gmail.com"],
            replyTo: [new Address($this->em, $this->nm)],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
