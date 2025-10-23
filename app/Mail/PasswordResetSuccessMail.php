<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ipAddress;
    public $device;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?string $ipAddress = null, ?string $device = null)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->device = $device;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Successful - SalesPulse',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-success',
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

