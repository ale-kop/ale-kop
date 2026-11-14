<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Triagem enviada pela página da consultoria. Responder vai direto para quem preencheu. */
class ConsultingLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name: string, email: string, phone: string, business: string, problem: string, current_process: string, monthly_cost: ?string, decision_maker: string}  $lead
     */
    public function __construct(public readonly array $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consultoria: novo caso de '.$this->lead['name'],
            replyTo: [new Address($this->lead['email'], $this->lead['name'])],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.consulting-lead', with: [
            'whatsappUrl' => 'https://wa.me/'.$this->whatsappDigits(),
        ]);
    }

    /** Número com DDI 55 para o link do WhatsApp (quem preenche quase nunca digita o DDI). */
    private function whatsappDigits(): string
    {
        $digits = preg_replace('/\D/', '', $this->lead['phone']);

        return strlen($digits) <= 11 ? '55'.$digits : $digits;
    }
}
