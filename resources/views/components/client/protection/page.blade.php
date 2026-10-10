@props(['title'])

@php
    $contactHotline = $globalContact->hotline ?? '0966 55 8808';
    $contactEmail = data_get($globalContact, 'email', 'gshaithanh@gmail.com');
@endphp

<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>{{ $title }} - Thanh Hải Ceramics</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" />
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: #F5EDE9;
            color: #2E2F2A;
            font-family: "Archivo", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.7;
        }

        main {
            width: 100%;
            max-width: 640px;
            padding: 40px 32px;
            background: #FFF;
            border-top: 4px solid #C76E00;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(46, 47, 42, 0.08);
        }

        h1 {
            margin: 0 0 24px;
            font-size: 28px;
            line-height: 1.3;
            font-weight: 600;
        }

        p { margin: 0 0 16px; }

        .record { font-weight: 600; }

        .contact {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #EFE4DE;
        }

        a { color: #C76E00; }

        .home-link {
            display: inline-block;
            margin-top: 8px;
            padding: 10px 24px;
            background: #2E2F2A;
            color: #FFF;
            border-radius: 4px;
            text-decoration: none;
        }

        .home-link:hover { background: #C76E00; }

        @media (max-width: 480px) {
            main { padding: 28px 20px; }
            h1 { font-size: 24px; }
        }
    </style>
</head>

<body>
    <main>
        <h1>{{ $title }}</h1>

        {{ $slot }}

        <p class="contact">
            {{ $contact }}
            Hotline <a href="tel:{{ preg_replace('/\D+/', '', $contactHotline) }}">{{ $contactHotline }}</a>,
            email <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.
        </p>

        <a class="home-link" href="{{ route('client.home') }}">Về trang chủ</a>
    </main>
</body>

</html>
