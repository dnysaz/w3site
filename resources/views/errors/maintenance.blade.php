<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Maintenance | w3site</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0a0a0c; /* Hitam khas w3site */
        }
        .glow {
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-slate-200 p-6">

    <div class="max-w-lg w-full text-center space-y-8">
        <div class="flex justify-center">
            <div class="p-4 bg-blue-600/10 rounded-2xl border border-blue-500/30 glow animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="space-y-4">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                Dashboard <span class="text-blue-500">Under Maintenance</span>
            </h1>
            <p class="text-lg text-slate-400">
                Halo Kak! Kami sedang melakukan optimasi berkala pada sistem <span class="text-blue-400 font-semibold">w3site.id</span>. 
                Tenang saja, halaman landing page publik tetap aktif seperti biasa.
            </p>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <div class="inline-flex items-center space-x-2 text-sm text-slate-500 mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span>Proses pembaruan sedang berlangsung...</span>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200 transform hover:scale-105 shadow-lg shadow-blue-900/20">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <p class="text-xs text-slate-600">
            &copy; 2026 w3site.id - Membangun Digital Masa Depan.
        </p>
    </div>

</body>
</html>