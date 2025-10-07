<?php

namespace App\Mail;

use App\Models\CutoffDate;
use App\Models\User;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class NewConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public bool $isChange = false, public string $url = '')
    {
        //
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
            subject: 'Your Nelson Waldorf School Grocery card order',
            replyTo: 'nwsgrocerycards@gmail.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.newconfirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->user->isCreditcard() || 
            $this->user->saveon + $this->user->coop + $this->user->saveon_onetime + $this->user->coop_onetime == 0 
            ) return [];
        
        return [
            Attachment::fromData(function () {
                $agreementView = View::make('components.debit-terms');
                return "<html><body>{$agreementView->render()}</body></html>'";
            }, 'debitagreement.html')->withMime('text/html')
        ];
    }
}
