<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use App\Services\FormTimeTrap;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(FormTimeTrap $timeTrap)
    {
        return view('contact.show', ['formToken' => $timeTrap->issue()]);
    }

    public function send(ContactRequest $request, FormTimeTrap $timeTrap)
    {
        // Resposta de sucesso igual à real, para o bot não saber que foi barrado
        if (! $timeTrap->passes($request->input('form_token'))) {
            Log::info('Contato barrado pela trava de tempo', ['ip' => $request->ip()]);

            return $this->successResponse();
        }

        Mail::to(config('mail.contact_address', 'contato@alekop.com'))
            ->queue(new ContactMail(
                senderName: $request->input('name'),
                senderEmail: $request->input('email'),
                contactSubject: 'Contato AleKop.com',
                body: $request->input('message'),
            ));

        return $this->successResponse();
    }

    private function successResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Mensagem enviada! Retornarei em breve.',
            'title' => 'Obrigado',
            'type' => 'success',
            'reload' => 1,
        ]);
    }
}
