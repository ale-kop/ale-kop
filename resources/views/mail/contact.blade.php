<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato via site</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
        .card { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
        .header { background: #111827; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .header p { color: #9ca3af; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px; }
        .field { margin-bottom: 20px; }
        .label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 15px; color: #111827; }
        .message-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
        .footer { padding: 16px 32px; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Nova mensagem de contato</h1>
            <p>Recebida pelo formulário em alekop.com</p>
        </div>
        <div class="body">
            <div class="field">
                <div class="label">De</div>
                <div class="value">{{ $senderName }} &lt;{{ $senderEmail }}&gt;</div>
            </div>
            <div class="field">
                <div class="label">Assunto</div>
                <div class="value">{{ $contactSubject }}</div>
            </div>
            <div class="field">
                <div class="label">Mensagem</div>
                <div class="message-box">{{ $body }}</div>
            </div>
        </div>
        <div class="footer">
            <p>Para responder, basta usar a função "Responder" do seu e-mail — a resposta irá para {{ $senderEmail }}.</p>
        </div>
    </div>
</body>
</html>
