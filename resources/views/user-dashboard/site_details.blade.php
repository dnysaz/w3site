<x-user-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-[1000] text-gray-800 hover:text-blue-600 underline tracking-tight">
                    https://{{ $site->subdomain }}.w3site.id
                </h1>
            </div>
            <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="px-6 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl">
                Kunjungi Situs <i class="fa-solid fa-external-link ml-2"></i>
            </a>
        </div>
    
        {{-- Statistik Atas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Total Pengunjung</p>
                <h3 class="text-4xl font-[1000] text-slate-900">{{ number_format($site->clicks_count ?? 0) }}</h3>
                <p class="mt-2 text-green-500 font-bold text-[10px] uppercase">Lifetime Clicks</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Method</p>
                <div class="flex items-center gap-3">
                    <i class="fa-{{ $isGit ? 'brands fa-github' : 'solid fa-file-zipper' }} text-xl text-slate-400"></i>
                    <h4 class="text-sm font-black text-slate-900 uppercase">{{ $isGit ? 'GitHub Sync' : 'ZIP File' }}</h4>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Dibuat</p>
                <h4 class="text-sm font-black text-slate-900">{{ $site->created_at->format('d M Y') }}</h4>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4">Terakhir Update</p>
                <h4 class="text-sm font-black text-slate-900">{{ $site->updated_at->diffForHumans() }}</h4>
            </div>
        </div>
    
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Bagian Grafik --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm">
                    <h3 class="font-black text-slate-900 text-lg uppercase mb-8">Grafik Kunjungan 7 Hari Terakhir</h3>
                    <div class="h-72">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
            </div>
    
            {{-- Detail Lainnya --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm h-fit">
                <h3 class="font-black text-slate-900 text-lg mb-6 uppercase">Detail Asset</h3>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-50 pb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase">Disk Usage</span>
                        <span class="text-xs font-black text-slate-900">{{ $siteSizeMb }} MB</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase">SSL</span>
                        <span class="text-xs font-black text-green-500 uppercase">Secured</span>
                    </div>
                </div>

                {{-- <div class="mt-10">
                    <button @click="openDeleteModal({{ $site->id }}, '{{ $site->subdomain }}')" class="w-full py-4 bg-red-50 text-red-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                        Hapus Situs
                    </button>
                </div> --}}
            </div>
        </div>
        {{-- Tracking Setup Box --}}
        <div class="mt-10 bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
            {{-- Dekorasi Latar Belakang --}}
            <div class="absolute top-0 right-0 p-10 opacity-10 group-hover:rotate-12 transition-transform duration-700 pointer-events-none">
                <i class="fa-solid fa-chart-line text-8xl text-white"></i>
            </div>

            <div class="relative z-10">
                {{-- Status Badge --}}
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    <h3 class="text-white font-black text-lg uppercase tracking-tight">Aktifkan Tracking Pengunjung</h3>
                </div>
                
                <p class="text-slate-400 text-sm mb-6 leading-relaxed max-w-xl">
                    Ingin melihat grafik kunjungan secara real-time? Salin kode tracking di bawah ini dan tempelkan di dalam tag 
                    <code class="text-blue-400 font-bold">&lt;head&gt;</code> atau 
                    <code class="text-blue-400 font-bold">&lt;body&gt;</code> pada file 
                    <code class="text-white italic">index.html</code> Anda.
                </p>

                {{-- Code Area --}}
                <div class="bg-slate-900 rounded-2xl p-4 border border-slate-700 flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <code id="trackingCode" class="text-blue-300 text-[14px] font-mono break-all leading-relaxed">
                            &lt;img src="{{ route('track.pixel', $site->subdomain) }}" style="display:none;"&gt;
                        </code>
                    </div>
                    
                    <button id="copyBtn" onclick="copyTrackingCode()" class="shrink-0 w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                        <i id="copyIcon" class="fa-solid fa-copy"></i>
                        <span id="copyText">Salin Kode</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
        function copyTrackingCode() {
            const codeElement = document.getElementById('trackingCode');
            const btn = document.getElementById('copyBtn');
            const btnText = document.getElementById('copyText');
            const btnIcon = document.getElementById('copyIcon');
            
            // Mengambil text murni dan membersihkan spasi tak terlihat
            const textToCopy = codeElement.textContent.trim();

            // Fungsi untuk mengubah state UI
            const setSuccessState = () => {
                // Simpan state awal
                const originalText = btnText.innerText;
                
                // Ubah tampilan menjadi Sukses
                btn.classList.replace('bg-blue-600', 'bg-emerald-500');
                btn.classList.replace('hover:bg-blue-500', 'hover:bg-emerald-400');
                btnText.innerText = 'Copied!';
                btnIcon.classList.replace('fa-copy', 'fa-check');

                // Kembalikan ke asal setelah 2 detik
                setTimeout(() => {
                    btn.classList.replace('bg-emerald-500', 'bg-blue-600');
                    btn.classList.replace('hover:bg-emerald-400', 'hover:bg-blue-500');
                    btnText.innerText = originalText;
                    btnIcon.classList.replace('fa-check', 'fa-copy');
                }, 2000);
            };

            // Eksekusi Copy
            if (navigator.clipboard && window.isSecureContext) {
                // Method modern untuk HTTPS
                navigator.clipboard.writeText(textToCopy).then(setSuccessState);
            } else {
                // Fallback untuk non-HTTPS atau browser lama
                const textArea = document.createElement("textarea");
                textArea.value = textToCopy;
                textArea.style.position = "fixed"; // Hindari scrolling
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    setSuccessState();
                } catch (err) {
                    console.error('Gagal menyalin:', err);
                }
                document.body.removeChild(textArea);
            }
        }
        </script>
    </div>

    {{-- Chart.js Implementation --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('visitorChart').getContext('2d');
            
            // Mengambil data asli dari PHP
            const labels = @json($chartLabels);
            const dataValues = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: dataValues,
                        borderColor: '#3b82f6',
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, canvas, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { stepSize: 1, font: { weight: 'bold', size: 10 } },
                            grid: { borderDash: [5, 5], color: '#f1f5f9' }
                        },
                        x: { 
                            ticks: { font: { weight: 'bold', size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-user-layout>