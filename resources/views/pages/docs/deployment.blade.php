<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Guide - w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .step-number {
            -webkit-text-stroke: 1px rgba(15, 23, 42, 0.1);
            color: transparent;
        }
    </style>
</head>
<body class="bg-white antialiased">

    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black italic text-sm">w3</div>
                <span class="text-xl font-black tracking-tighter text-slate-900">w3site<span class="text-blue-600">.id</span></span>
            </a>
            <a href="/" class="text-xs font-black tracking-widest text-slate-400 hover:text-blue-600 transition-colors uppercase">Kembali ke Home</a>
        </div>
    </nav>

    <main class="relative pt-40 pb-24 overflow-hidden">
        
        {{-- Background Text --}}
        <div class="absolute top-20 left-1/2 -translate-x-1/2 select-none pointer-events-none z-0">
            <h1 class="text-[12rem] md:text-[25rem] font-[1000] text-slate-50 tracking-tighter leading-none italic uppercase">
                Deploy
            </h1>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-4xl mb-24">
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] mb-6 uppercase">Panduan Deployment</p>
                <h2 class="text-6xl md:text-8xl font-[1000] text-slate-900 tracking-tighter italic leading-[0.9]">
                    Onlinekan Situsmu <span class="text-blue-600">Dalam Sekejap.</span>
                </h2>
                <p class="mt-8 text-xl text-slate-500 font-medium leading-relaxed">
                    Kami memangkas kerumitan teknis. Pilih metode yang paling nyaman bagimu dan biarkan infrastruktur kami menangani sisanya.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-32">
                
                {{-- Metode 1: ZIP File --}}
                <div class="group">
                    <div class="bg-slate-900 rounded-[3rem] p-10 md:p-14 text-white h-full relative overflow-hidden transition-transform hover:-translate-y-2">
                        <div class="relative z-10">
                            <span class="step-number text-8xl font-black italic absolute -top-6 -right-4">01</span>
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-8">
                                <i class="fa-solid fa-file-zipper text-xl"></i>
                            </div>
                            <h3 class="text-3xl font-[1000] italic mb-6">Upload ZIP File</h3>
                            <p class="text-slate-400 leading-relaxed mb-8">
                                Metode tercepat untuk situs statis. Cukup kompres folder project-mu dan unggah langsung ke dashboard kami.
                            </p>
                            <ul class="space-y-4 mb-8 text-sm font-medium">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check text-blue-500 mt-1"></i>
                                    Pastikan ada file <span class="text-white italic font-bold">index.html</span> di root folder.
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check text-blue-500 mt-1"></i>
                                    Kompres semua aset (CSS, JS, Images) menjadi satu file .zip.
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check text-blue-500 mt-1"></i>
                                    Drag & drop di halaman <span class="text-blue-500 italic">Deploy Site</span>.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Metode 2: GitHub Sync --}}
                <div class="group">
                    <div class="bg-white border-2 border-slate-100 rounded-[3rem] p-10 md:p-14 h-full relative overflow-hidden transition-transform hover:-translate-y-2 shadow-xl shadow-slate-100/50">
                        <div class="relative z-10">
                            <span class="step-number text-8xl font-black italic absolute -top-6 -right-4">02</span>
                            <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center mb-8 text-white">
                                <i class="fa-brands fa-github text-xl"></i>
                            </div>
                            <h3 class="text-3xl font-[1000] italic mb-6 text-slate-900">GitHub Sync</h3>
                            <p class="text-slate-500 leading-relaxed mb-8">
                                Hubungkan repositori GitHub-mu untuk kemudahan pembaruan data secara otomatis.
                            </p>
                            <div class="space-y-6">
                                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Langkah Mudah:</p>
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed italic">
                                        Copy Link Project GitHub Anda &rarr; Paste di Kolom Repository URL &rarr; Klik Deploy.
                                    </p>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium">
                                    * Pastikan repositori bersifat <span class="text-slate-900 font-bold uppercase">Public</span> agar sistem kami dapat mengakses file project Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Info Tambahan --}}
            <div class="bg-blue-600 rounded-[3rem] p-10 md:p-16 text-white relative overflow-hidden">
                <i class="fa-solid fa-bolt-lightning absolute -bottom-10 -right-10 text-white/10 text-[20rem]"></i>
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
                    <div class="md:col-span-8">
                        <h3 class="text-4xl font-[1000] italic mb-6">Siap Untuk Live?</h3>
                        <p class="text-blue-100 text-lg leading-relaxed max-w-2xl">
                            Setelah proses deploy selesai, situs Anda akan langsung mendapatkan subdomain <span class="bg-white/20 px-2 py-1 rounded">namasitus.w3site.id</span> secara otomatis dan sertifikat SSL (HTTPS) gratis selamanya.
                        </p>
                    </div>
                    <div class="md:col-span-4 flex md:justify-end">
                        <a href="/login" class="px-10 py-5 bg-white text-blue-600 rounded-2xl font-black italic tracking-tighter hover:bg-slate-900 hover:text-white transition-all transform hover:scale-105 shadow-xl shadow-blue-900/20">
                            Mulai Deploy Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-12 text-center border-t border-slate-100 pt-20">
                <div>
                    <div class="text-blue-600 mb-4"><i class="fa-solid fa-shield-halved text-2xl"></i></div>
                    <h4 class="text-lg font-black italic mb-2 uppercase tracking-tighter">Aman & Terlindungi</h4>
                    <p class="text-slate-500 text-sm">Setiap deployment dilengkapi dengan proteksi DDoS dan SSL terenkripsi.</p>
                </div>
                <div>
                    <div class="text-blue-600 mb-4"><i class="fa-solid fa-gauge-high text-2xl"></i></div>
                    <h4 class="text-lg font-black italic mb-2 uppercase tracking-tighter">Performa NVMe</h4>
                    <p class="text-slate-500 text-sm">Situsmu akan dimuat secepat kilat berkat penyimpanan SSD NVMe modern.</p>
                </div>
                <div>
                    <div class="text-blue-600 mb-4"><i class="fa-solid fa-cloud-arrow-up text-2xl"></i></div>
                    <h4 class="text-lg font-black italic mb-2 uppercase tracking-tighter">Unlimited Update</h4>
                    <p class="text-slate-500 text-sm">Update file situsmu kapan saja tanpa batasan frekuensi deployment.</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-12 text-center">
        <p class="text-[10px] font-black text-slate-400 tracking-widest uppercase">&copy; 2026 w3site.id — Deployment Engine v1.0</p>
    </footer>

</body>
</html>