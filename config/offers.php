<?php

/*
|--------------------------------------------------------------------------
| Páginas de oferta
|--------------------------------------------------------------------------
| Cada oferta aponta para um curso cadastrado no admin. Nome, preço e
| acesso vêm do curso (extra.price, extra.access = paid); a copy fica na view.
| A consultoria não tem curso nem checkout: termina num formulário de triagem.
*/

return [

    'whatsapp' => [
        'course_slug' => env('OFFER_WHATSAPP_COURSE_SLUG', 'orcamentos-e-propostas-pelo-whatsapp'),
    ],

    'consulting' => [
        // Número que recebe o áudio de triagem (só dígitos, com DDI: 5519...).
        // Vazio esconde a opção de áudio da página.
        'whatsapp_number' => env('CONSULTING_WHATSAPP_NUMBER'),
    ],

];
