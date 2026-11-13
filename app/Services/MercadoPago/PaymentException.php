<?php

namespace App\Services\MercadoPago;

use RuntimeException;

/**
 * Falha ao criar um pagamento. A mensagem é segura para mostrar ao comprador.
 */
class PaymentException extends RuntimeException {}
