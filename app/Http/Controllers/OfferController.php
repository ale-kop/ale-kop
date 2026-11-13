<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
}
