<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\FormTimeTrap;
use Illuminate\View\View;

class OfferController extends Controller
{
    /**
     * Página de vendas do curso "Orçamentos e propostas comerciais pelo WhatsApp".
     */
    public function whatsapp(): View
    {
        $course = Course::query()
            ->where('slug', config('offers.whatsapp.course_slug'))
            ->firstOrFail();

        return view('offers.whatsapp', [
            'course' => $course,
            'maxInstallments' => config('services.mercadopago.max_installments'),
        ]);
    }

    /**
     * Página da consultoria AKOP: sem preço fixo nem checkout, termina no formulário de triagem.
     */
    public function consulting(FormTimeTrap $timeTrap): View
    {
        $number = preg_replace('/\D/', '', (string) config('offers.consulting.whatsapp_number'));

        return view('offers.consultoria', [
            'formToken' => $timeTrap->issue(),
            'whatsappUrl' => $number !== ''
                ? 'https://wa.me/'.$number.'?text='.rawurlencode('Oi, Ale! Vou te mandar um áudio contando o meu caso.')
                : null,
        ]);
    }
}
