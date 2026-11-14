<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultingLeadRequest;
use App\Mail\ConsultingLeadMail;
use App\Services\FormTimeTrap;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConsultingLeadController extends Controller
{
    public function store(ConsultingLeadRequest $request, FormTimeTrap $timeTrap): JsonResponse
    {
        // Resposta igual à real, para o bot não saber que foi barrado
        if (! $timeTrap->passes($request->input('form_token'))) {
            Log::info('Triagem da consultoria barrada pela trava de tempo', ['ip' => $request->ip()]);

            return $this->successResponse();
        }

        Mail::to(config('mail.contact_address', 'contato@alekop.com'))
            ->queue(new ConsultingLeadMail($request->safe()->except('website')));

        return $this->successResponse();
    }

    private function successResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Recebi o seu caso. Respondo em até 2 dias úteis pelo WhatsApp ou por e-mail.',
            'title' => 'Caso enviado',
            'type' => 'success',
            // Troca o formulário pela confirmação, sem recarregar a página
            'js' => "document.querySelector('[data-lead-form]').hidden = true; document.querySelector('[data-lead-done]').hidden = false;",
        ]);
    }
}
