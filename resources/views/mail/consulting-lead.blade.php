<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo caso para a consultoria</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
        .card { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
        .header { background: #144A6E; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .header p { color: #cfe0ee; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px; }
        .field { margin-bottom: 22px; }
        .label { font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 15px; line-height: 1.6; color: #111827; white-space: pre-wrap; }
        .button { display: inline-block; background: #1A7F4E; color: #fff !important; text-decoration: none; font-weight: 600; font-size: 14px; padding: 10px 18px; border-radius: 8px; }
        .footer { padding: 16px 32px; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>{{ $lead['name'] }} contou o caso</h1>
            <p>Formulário de triagem em alekop.com/consultoria</p>
        </div>
        <div class="body">
            <div class="field">
                <div class="label">Contato</div>
                <div class="value">{{ $lead['email'] }} · {{ $lead['phone'] }}</div>
                <p style="margin: 12px 0 0;"><a class="button" href="{{ $whatsappUrl }}">Responder no WhatsApp</a></p>
            </div>
            @foreach([
                'business' => 'O que a empresa faz e quantas pessoas trabalham nela',
                'problem' => 'Problema que quer resolver',
                'current_process' => 'Como é feito hoje',
                'monthly_cost' => 'Quanto custa por mês',
                'decision_maker' => 'Quem decide',
            ] as $key => $label)
                @if(filled($lead[$key] ?? null))
                    <div class="field">
                        <div class="label">{{ $label }}</div>
                        <div class="value">{{ $lead[$key] }}</div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="footer">
            <p>"Responder" no e-mail vai para {{ $lead['email'] }}.</p>
        </div>
    </div>
</body>
</html>
