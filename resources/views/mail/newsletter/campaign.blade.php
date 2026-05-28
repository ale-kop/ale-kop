<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
    <style>
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f0; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .header { background: #1e3a5f; padding: 28px 40px; text-align: center; }
        .header a { color: #ffffff; font-size: 20px; font-weight: 800; text-decoration: none; letter-spacing: -0.5px; }
        .body { padding: 36px 40px; }
        .body h1, .body h2, .body h3 { color: #111827; line-height: 1.3; }
        .body p { color: #374151; line-height: 1.7; }
        .body a { color: #2563eb; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; }
        .footer p { margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.6; }
        .footer a { color: #6b7280; }
        @media (max-width: 640px) {
            .wrapper { margin: 0; border-radius: 0; }
            .header, .body, .footer { padding-left: 24px; padding-right: 24px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <a href="{{ url('/') }}">Ale Kop</a>
    </div>

    <div class="body">
        {!! $campaign->content !!}
    </div>

    <div class="footer">
        <p>
            Você está recebendo este e-mail porque se cadastrou na newsletter de Ale Kop.<br>
            <a href="{{ route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]) }}">Descadastrar</a>
        </p>
    </div>
</div>
</body>
</html>
