<?php

/*
|--------------------------------------------------------------------------
| Páginas de oferta
|--------------------------------------------------------------------------
| Cada oferta aponta para um curso cadastrado no admin. Nome, preço e
| acesso vêm do curso (extra.price, extra.access = paid); a copy fica na view.
*/

return [

    'whatsapp' => [
        'course_slug' => env('OFFER_WHATSAPP_COURSE_SLUG', 'orcamentos-e-propostas-pelo-whatsapp'),
    ],

];
