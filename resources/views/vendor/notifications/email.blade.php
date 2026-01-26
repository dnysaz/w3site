<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background-color: #ffffff; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .email-container { max-width: 600px; margin: 0 auto; padding: 60px 20px; }
        .logo { margin-bottom: 60px; text-align: left; }
        .greeting { font-size: 22px; font-weight: 800; color: #000000; letter-spacing: -1px; margin-bottom: 24px; }
        .text { font-size: 18px; line-height: 1.6; color: #444444; margin-bottom: 20px; }
        .button-wrapper { margin: 40px 0; }
        .button { background-color: #000000; color: #ffffff !important; padding: 18px 32px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 16px; display: inline-block; }
        .footer { margin-top: 80px; padding-top: 20px; border-top: 1px solid #eeeeee; color: #999999; font-size: 13px; }
        .footer a { color: #000000; text-decoration: none; font-weight: 600; }
        
        @media only screen and (max-width: 480px) {
            .greeting { font-size: 28px; }
            .text { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo">
            <img src="https://raw.githubusercontent.com/dnysaz/images/main/1.png" alt="w3site.id" width="140">
        </div>

        <div class="greeting">
            {{ $greeting ?? 'Halo!' }}
        </div>

        <div class="text">
            @foreach ($introLines as $line)
                <p>{{ $line }}</p>
            @endforeach
        </div>

        @if (isset($actionText))
            <div class="button-wrapper">
                <a href="{{ $actionUrl }}" class="button">
                    {{ $actionText }} &rarr;
                </a>
            </div>
        @endif

        <div class="text">
            @foreach ($outroLines as $line)
                <p>{{ $line }}</p>
            @endforeach
        </div>

        <div class="footer">
            <p>Dikirim oleh <strong>w3site.id</strong> &bull; AI Site Builder & Hosting Gratis.</p>
            <p>
                <a href="https://w3site.id">Website</a> &nbsp;&bull;&nbsp; 
                <a href="https://w3site.id/dashboard">Dashboard</a>
            </p>
        </div>
    </div>
</body>
</html>