<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string|null  $passwordToken  presente quando a conta foi criada no checkout
     */
    public function __construct(
        public readonly Order $order,
        public readonly ?string $passwordToken = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Seu acesso ao curso '.$this->order->course->name);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-approved',
            with: [
                'courseUrl' => route('courses.show', $this->order->course->slug),
                'passwordUrl' => $this->passwordToken
                    ? route('password.reset', ['token' => $this->passwordToken, 'email' => $this->order->email])
                    : null,
            ],
        );
    }
}
