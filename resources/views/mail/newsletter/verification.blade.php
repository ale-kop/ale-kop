<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirme sua inscrição</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
        .card { max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
        .header { background: #111827; padding: 24px 32px; }
        .header h1 { color: #fff; font-size: 18px; margin: 0; font-weight: 600; }
        .body { padding: 32px; text-align: center; }
        .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 20px; }
        .code { display: inline-block; font-size: 36px; font-weight: 700; letter-spacing: 0.35em; padding: 14px 14px 14px 26px; background: #f3f4f6; border-radius: 10px; color: #111827; }
        .footer { padding: 16px 32px; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Confirme sua inscrição</h1>
        </div>
        <div class="body">
            <p>Use o código abaixo para confirmar seu e-mail na newsletter do Ale Kop:</p>
            <div class="code">{{ $code }}</div>
            <p style="margin-top: 20px;">O código vale por {{ $minutes }} minutos.</p>
        </div>
        <div class="footer">
            <p>Se não foi você quem pediu, ignore este e-mail: nada será feito sem o código.</p>
        </div>
    </div>
</body>
</html>
