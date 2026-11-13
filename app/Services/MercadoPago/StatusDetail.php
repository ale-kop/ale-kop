<?php

namespace App\Services\MercadoPago;

/**
 * Traduz o status_detail de um pagamento recusado em uma orientação para o comprador.
 */
class StatusDetail
{
    private const MESSAGES = [
        'cc_rejected_bad_filled_card_number' => 'Confira o número do cartão.',
        'cc_rejected_bad_filled_date' => 'Confira a data de validade do cartão.',
        'cc_rejected_bad_filled_security_code' => 'Confira o código de segurança do cartão.',
        'cc_rejected_bad_filled_other' => 'Confira os dados do cartão.',
        'cc_rejected_insufficient_amount' => 'O cartão não tem limite suficiente. Tente outro cartão ou pague com Pix.',
        'cc_rejected_call_for_authorize' => 'O banco pediu autorização para este pagamento. Ligue para o seu banco e tente de novo.',
        'cc_rejected_card_disabled' => 'O cartão está inativo. Ative-o com o seu banco ou use outro cartão.',
        'cc_rejected_duplicated_payment' => 'Você já fez um pagamento com este valor. Se precisar pagar de novo, use outro cartão ou Pix.',
        'cc_rejected_max_attempts' => 'Você atingiu o limite de tentativas. Use outro cartão ou pague com Pix.',
        'cc_rejected_high_risk' => 'O pagamento foi recusado pela análise de segurança. Tente com Pix.',
        'cc_rejected_blacklist' => 'O pagamento foi recusado. Tente outro cartão ou pague com Pix.',
        'cc_rejected_card_error' => 'O cartão não pôde ser processado. Tente outro cartão ou pague com Pix.',
    ];

    public static function message(?string $detail): string
    {
        return self::MESSAGES[$detail] ?? 'O pagamento foi recusado. Tente outro cartão ou pague com Pix.';
    }
}
