<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Geist', sans-serif; }
    </style>
</head>
<body class="bg-white text-black antialiased">
    <div class="flex flex-col items-center justify-center min-h-screen text-center px-6">
        <div class="flex items-center gap-6">
            <h1 class="text-[24px] font-bold tracking-tight border-r border-[#eaeaea] pr-6">@yield('code')</h1>
            <p class="text-[14px] font-semibold">@yield('message')</p>
        </div>
        <div class="mt-8">
            <a href="/" class="text-[13px] font-bold text-[#666] hover:text-black transition-colors underline underline-offset-4 decoration-[#eaeaea] hover:decoration-black">Return Home</a>
        </div>
    </div>
</body>
</html>
