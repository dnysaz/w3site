<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation Full - w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        .docs-content h3 { font-size: 2.25rem; font-weight: 1000; font-style: italic; color: #0f172a; margin-top: 4rem; margin-bottom: 1.5rem; letter-spacing: -0.05em; line-height: 1; border-left: 8px solid #2563eb; padding-left: 1.5rem; }
        .docs-content h4 { font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-top: 2.5rem; margin-bottom: 1rem; display: flex; items-center; gap: 0.5rem; }
        .docs-content p { color: #64748b; line-height: 1.8; margin-bottom: 1.5rem; }
        .code-display { background: #0f172a; color: #f8fafc; padding: 1.5rem; border-radius: 1.5rem; font-family: 'Fira Code', monospace; font-size: 0.85rem; margin-bottom: 1.5rem; border: 1px solid #1e293b; overflow-x: auto; }
        .sidebar-link { transition: all 0.3s; border-left: 4px solid transparent; }
        .sidebar-link.active { color: #2563eb; font-weight: 800; border-left: 4px solid #2563eb; background: #f8faff; }
        .feature-card { border: 1px solid #f1f5f9; border-radius: 2.5rem; padding: 2.5rem; background: #fff; transition: all 0.3s; }
        .feature-card:hover { border-color: #dbeafe; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-white antialiased">

    {{-- Navigation --}}
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black italic text-sm">w3</div>
                <span class="text-xl font-black tracking-tighter text-slate-900">w3site<span class="text-blue-600">.id</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-xs font-black tracking-widest text-blue-600 uppercase border-2 border-blue-600 px-5 py-2.5 rounded-2xl hover:bg-blue-600 hover:text-white transition-all">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 pt-32 pb-24">
        <div class="flex flex-col lg:flex-row gap-16">
            
            {{-- Sidebar Navigasi --}}
            <aside class="lg:w-72 flex-shrink-0 lg:sticky lg:top-32 h-[calc(100vh-160px)] overflow-y-auto pr-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Navigasi Utama</p>
                <nav class="space-y-1">
                    <a href="#struktur" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">01. Struktur File</a>
                    <a href="#cara-zip" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">02. Upload ZIP</a>
                    <a href="#frameworks" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">03. Framework JS (Vite/Next)</a>
                    <a href="#cara-github" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">04. Integrasi GitHub</a>
                    <a href="#database" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">05. Database & BaaS</a>
                    <a href="#faq" class="sidebar-link block py-3 px-4 text-sm font-bold text-slate-500 hover:text-blue-600">06. FAQ</a>
                </nav>
            </aside>

            {{-- Konten Utama --}}
            <div class="flex-1 max-w-3xl docs-content">
                
                <section id="struktur">
                    <h2 class="text-6xl font-[1000] text-slate-900 tracking-tighter italic mb-8 leading-[0.9]">Aturan <span class="text-blue-600">Deployment.</span></h2>
                    <p class="text-lg">w3site.id menghosting situs Anda sebagai <span class="font-bold underline">Static Site</span>. Ini berarti server kami mencari file HTML siap saji untuk ditampilkan ke pengunjung.</p>
                    
                    <h4>Struktur Folder Standar</h4>
                    <p>Pastikan file <code class="text-blue-600 font-bold">index.html</code> Anda berada di folder paling luar (Root). Inilah yang akan pertama kali dibaca oleh sistem.</p>
                    <div class="code-display">
                        📁 my-website/<br>
                        ├── 📄 <span class="text-emerald-400">index.html</span> (Halaman Utama)<br>
                        ├── 📁 css/<br>
                        │   └── style.css<br>
                        ├── 📁 js/<br>
                        │   └── script.js<br>
                        └── 📁 images/<br>
                            └── logo.png
                    </div>
                </section>

                <section id="cara-zip">
                    <h3>Cara Upload ZIP</h3>
                    <p>Metode tercepat untuk web HTML/CSS/JS murni. Cukup bungkus project Anda menjadi ZIP dan kirim ke server kami.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-6 bg-slate-50 rounded-3xl">
                            <h5 class="font-black text-slate-900 text-xs uppercase mb-2">Langkah 1</h5>
                            <p class="text-xs">Masuk ke folder project, pilih semua file (Ctrl+A), klik kanan dan pilih **Compress to ZIP**.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl">
                            <h5 class="font-black text-slate-900 text-xs uppercase mb-2">Langkah 2</h5>
                            <p class="text-xs">Upload file ZIP tersebut ke dashboard w3site.id. Tunggu 3 detik, dan web Anda online!</p>
                        </div>
                    </div>
                </section>

                <section id="frameworks">
                    <h3>Modern Frameworks</h3>
                    <p>Kami mendukung framework JS modern. Kuncinya adalah melakukan **Static Export** sebelum melakukan upload.</p>
                    
                    <div class="space-y-6">
                        <div class="feature-card">
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-brands fa-js-square text-3xl text-black"></i>
                                <h4 class="mt-0 mb-0 italic uppercase tracking-tighter">Next.js (SSG)</h4>
                            </div>
                            <p class="text-sm">Ubah konfigurasi di <code class="font-bold">next.config.js</code> agar menghasilkan file statis:</p>
                            <div class="code-display text-xs">
                                const nextConfig = {<br>
                                &nbsp;&nbsp;output: 'export',<br>
                                }
                            </div>
                            <p class="text-[11px] mt-4">Jalankan <code class="font-bold">npm run build</code>. Upload hanya isi folder <span class="text-blue-600 font-bold">/out</span>.</p>
                        </div>

                        <div class="feature-card">
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-brands fa-react text-3xl text-blue-400"></i>
                                <h4 class="mt-0 mb-0 italic uppercase tracking-tighter">React / Vue / Vite</h4>
                            </div>
                            <p class="text-sm">Vite secara otomatis melakukan build ke aset statis. Anda hanya perlu menjalankan perintah:</p>
                            <div class="code-display text-xs">
                                npm run build
                            </div>
                            <p class="text-[11px] mt-4 italic">Setelah selesai, upload isi folder <span class="text-blue-600 font-bold">/dist</span> ke platform kami.</p>
                        </div>
                    </div>
                </section>

                <section id="cara-github">
                    <h3>Integrasi GitHub</h3>
                    <p>Malas upload manual? Hubungkan repositori GitHub Anda. Kami akan menarik data setiap kali Anda melakukan perubahan.</p>
                    
                    <div class="bg-blue-50 p-8 rounded-[2.5rem] border border-blue-100">
                        <h4 class="mt-0 text-blue-900 italic tracking-tighter uppercase">Cara Copy URL Repositori:</h4>
                        <ol class="list-decimal ml-5 space-y-3 text-sm text-blue-800 font-medium">
                            <li>Buka halaman repositori Anda di GitHub.</li>
                            <li>Klik tombol hijau <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-xs">Code</span>.</li>
                            <li>Pastikan tab **HTTPS** terpilih, lalu klik ikon salin (copy).</li>
                        </ol>
                        <div class="mt-6 bg-white p-4 rounded-xl border border-blue-200">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-2 leading-none">Contoh Link Yang Dimasukkan:</p>
                            <code class="text-blue-600 font-bold text-xs break-all">https://github.com/akun-anda/nama-project.git</code>
                        </div>
                        <p class="mt-4 text-[11px] text-blue-600 italic font-bold">* Pastikan repositori Anda bersifat PUBLIC.</p>
                    </div>
                </section>

                <section id="database">
                    <h3>Database & Penyimpanan Data</h3>
                    <p>Karena w3site.id bersifat statis, Anda tidak bisa menggunakan database MySQL tradisional di sini. Gunakan **BaaS** (Backend as a Service).</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-8 border border-slate-100 rounded-[2.5rem]">
                            <h5 class="font-black italic text-slate-900 mb-2">Supabase / Firebase</h5>
                            <p class="text-xs leading-relaxed">Gunakan SDK JavaScript mereka untuk fitur Login dan Database. Sangat kencang dan aman.</p>
                        </div>
                        <div class="p-8 border border-slate-100 rounded-[2.5rem]">
                            <h5 class="font-black italic text-slate-900 mb-2">SQLite (WASM)</h5>
                            <p class="text-xs leading-relaxed">Cocok untuk aplikasi offline. Database disimpan langsung di browser pengguna.</p>
                        </div>
                    </div>
                </section>

                <section id="faq" class="mb-20">
                    <h3>F.A.Q</h3>
                    <div class="space-y-4">
                        <details class="group p-6 border border-slate-100 rounded-3xl bg-white cursor-pointer">
                            <summary class="flex items-center justify-between font-bold text-slate-900 italic">
                                Kenapa muncul Error 404?
                                <span class="text-blue-600 group-open:rotate-180 transition-all"><i class="fa-solid fa-chevron-down"></i></span>
                            </summary>
                            <p class="text-sm mt-4 text-slate-500 border-t pt-4">Itu berarti file <strong>index.html</strong> Anda tidak ditemukan. Periksa apakah file tersebut ada di folder paling luar ZIP Anda, bukan di dalam sub-folder.</p>
                        </details>
                        <details class="group p-6 border border-slate-100 rounded-3xl bg-white cursor-pointer">
                            <summary class="flex items-center justify-between font-bold text-slate-900 italic">
                                Apakah mendukung PHP?
                                <span class="text-blue-600 group-open:rotate-180 transition-all"><i class="fa-solid fa-chevron-down"></i></span>
                            </summary>
                            <p class="text-sm mt-4 text-slate-500 border-t pt-4">Tidak. Kami fokus pada Static Hosting (HTML, CSS, JS). Untuk backend, gunakan API pihak ketiga atau layanan BaaS seperti Supabase.</p>
                        </details>
                    </div>
                </section>

                <div class="bg-gray-900 p-12 rounded-[4rem] text-white text-center">
                    <h2 class="text-4xl font-[1000] italic mb-4 tracking-tighter">Butuh Bantuan Personal?</h2>
                    <p class="text-blue-100 mb-10 max-w-lg mx-auto">Tim kami siap membantu proses deployment Anda jika mengalami kesulitan teknis.</p>
                    <a href="" class="inline-block px-10 py-5 bg-white text-blue-600 rounded-2xl font-black italic tracking-tighter hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1">
                        Chat WhatsApp Sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-slate-100 py-12 text-center">
        <p class="text-[10px] font-black text-slate-400 tracking-widest uppercase">&copy; 2026 w3site.id — Memajukan Web Indonesia</p>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section');
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>