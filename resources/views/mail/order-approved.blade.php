<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu acesso ao curso</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
        .card { max-width: 520px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
        .header { background: #144A6E; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .body { padding: 32px; }
        .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 18px; }
        .button { display: inline-block; background: #144A6E; color: #fff !important; text-decoration: none; font-weight: 600; font-size: 15px; padding: 12px 22px; border-radius: 8px; }
        .summary { margin: 24px 0 0; padding: 16px; background: #f3f4f6; border-radius: 8px; font-size: 14px; color: #374151; }
        .summary td { padding: 2px 0; }
        .footer { padding: 16px 32px; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Pagamento aprovado</h1>
        </div>
        <div class="body">
            <p>Olá, {{ $order->firstName() }}. Seu acesso ao curso <strong>{{ $order->course->name }}</strong> está liberado por 1 ano.</p>

            @if($passwordUrl)
                <p>Criamos uma conta para você com este e-mail. Defina sua senha para entrar e assistir às aulas:</p>
                <p><a class="button" href="{{ $passwordUrl }}">Criar minha senha</a></p>
                <p style="font-size: 13px; color: #6b7280;">O link vale por 60 minutos. Se expirar, use “Esqueci minha senha” na tela de login.</p>
            @else
                <p>Entre com a sua conta de sempre para assistir às aulas:</p>
                <p><a class="button" href="{{ $courseUrl }}">Abrir o curso</a></p>
            @endif

            <table class="summary" width="100%" cellpadding="0" cellspacing="0">
                <tr><td>Pedido</td><td align="right">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($order->uuid, 0, 8)) }}</td></tr>
                <tr><td>Valor</td><td align="right">{{ $order->formattedAmount() }}</td></tr>
                <tr><td>Forma de pagamento</td><td align="right">{{ $order->isPix() ? 'Pix' : ($order->installments > 1 ? "Cartão em {$order->installments}x" : 'Cartão à vista') }}</td></tr>
            </table>
        </div>
        <div class="footer">
            <p>Você tem 7 dias para pedir o reembolso, sem precisar explicar o motivo. É só responder a este e-mail.</p>
        </div>
    </div>
</body>
</html>
