<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.show');
    }

    public function send(ContactRequest $request)
    {
        Mail::to(config('mail.contact_address', 'contato@alekop.com'))
            ->send(new ContactMail(
                senderName:     $request->input('name'),
                senderEmail:    $request->input('email'),
                //contactSubject: $request->input('subject'),
                contactSubject: 'Contato AleKop.com',
                body:           $request->input('message'),
            ));

        return response()->json([
            'message' => 'Mensagem enviada! Retornarei em breve.',
            'title'   => 'Obrigado',
            'type'    => 'success',
            'reload' => 1
        ]);
    }
}
