<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
            System <span class="text-blue-600">Overview</span>
        </h2>
        <p class="text-slate-500 text-sm font-medium">Monitoring data real-time platform w3site.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all relative">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Revenue</p>
                    {{-- Tombol Toggle --}}
                    <button id="toggleRevenue" class="text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-eye text-[10px]" id="eyeIcon"></i>
                    </button>
                </div>
                {{-- Berikan ID pada Nominal dan simpan angka aslinya di data-attribute --}}
                <h3 id="revenueValue" 
                    class="text-2xl font-black text-slate-900 mt-1" 
                    data-full="Rp {{ number_format($stats['total_revenue']) }}">
                    Rp {{ number_format($stats['total_revenue']) }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-wallet text-lg"></i>
            </div>
        </div>
    
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Users</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_users']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
        </div>
    
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Sites</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_sites']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-globe text-lg"></i>
            </div>
        </div>
    
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Visits</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_visits']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bolt-lightning text-lg"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-600"></i> Tren Pendaftaran
            </h3>
            <div class="h-80">
                <canvas id="growthChart"></canvas> 
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-eye text-emerald-500"></i> Traffic Kunjungan
            </h3>
            <div class="h-80">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 text-center text-sm uppercase tracking-widest">Paket Pengguna</h3>
            <div class="h-64">
                <canvas id="userPackageChart"></canvas>
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 text-center text-sm uppercase tracking-widest">Tipe Website</h3>
            <div class="h-64">
                <canvas id="siteTypeChart"></canvas>
            </div>
        </div>

        <div class="bg-blue-600 p-8 rounded-[2.5rem] shadow-xl shadow-blue-100 relative overflow-hidden flex flex-col justify-center">
            <i class="fa-solid fa-chart-simple absolute -bottom-4 -right-4 text-white/10 text-9xl rotate-12"></i>
            <div class="relative z-10 text-white">
                <h3 class="font-black text-blue-100 mb-6 uppercase tracking-widest text-[10px]">Content Distribution</h3>
                <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-6">
                    <div class="border-r border-white/10 pr-4">
                        <div class="flex items-center gap-2 mb-2 text-blue-200">
                            <i class="fa-solid fa-link text-[10px]"></i>
                            <span class="text-[10px] font-black uppercase tracking-tighter">Shortlinks</span>
                        </div>
                        <h2 class="text-4xl font-black mb-1">{{ number_format($stats['total_shortlinks']) }}</h2>
                    </div>
                    <div class="pl-4">
                        <div class="flex items-center gap-2 mb-2 text-blue-200">
                            <i class="fa-solid fa-share-nodes text-[10px]"></i>
                            <span class="text-[10px] font-black uppercase tracking-tighter">Bio Links</span>
                        </div>
                        <h2 class="text-4xl font-black mb-1">{{ number_format($stats['total_linktrees']) }}</h2>
                    </div>
                </div>
                <p class="mt-8 text-blue-100/80 text-[11px] leading-relaxed font-medium bg-white/10 p-3 rounded-2xl inline-block">
                    <i class="fa-solid fa-circle-info mr-1"></i> Data akumulasi konten global.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createGradient = (ctx, chartArea, color) => {
                if (!chartArea) return null;
                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                gradient.addColorStop(0, 'rgba(' + color + ', 0)');
                gradient.addColorStop(1, 'rgba(' + color + ', 0.1)');
                return gradient;
            };
    
            // 1. Growth Chart
            const ctx1 = document.getElementById('growthChart')?.getContext('2d');
            if(ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: @json($chartData->pluck('date')),
                        datasets: [{
                            label: 'User Baru',
                            data: @json($chartData->pluck('total')),
                            borderColor: '#3b82f6',
                            backgroundColor: (context) => createGradient(context.chart.ctx, context.chart.chartArea, '59, 130, 246'),
                            fill: true, tension: 0.4, borderWidth: 4, pointRadius: 6, pointBackgroundColor: '#fff', pointBorderColor: '#3b82f6', pointBorderWidth: 3
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }
    
            // 2. Traffic Chart
            const ctx2 = document.getElementById('trafficChart')?.getContext('2d');
            if(ctx2) {
                new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: @json($visitorChartData->pluck('date')),
                        datasets: [{
                            label: 'Kunjungan',
                            data: @json($visitorChartData->pluck('total')),
                            borderColor: '#10b981',
                            backgroundColor: (context) => createGradient(context.chart.ctx, context.chart.chartArea, '16, 185, 129'),
                            fill: true, tension: 0.4, borderWidth: 4, pointRadius: 6, pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointBorderWidth: 3
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }
    
            // 3. User Package Chart
            const ctx3 = document.getElementById('userPackageChart')?.getContext('2d');
            if(ctx3) {
                new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Gratis', 'Pemula', 'Pro'],
                        datasets: [{
                            data: [{{ $userStats['gratis'] }}, {{ $userStats['pemula'] }}, {{ $userStats['pro'] }}],
                            backgroundColor: ['#cbd5e1', '#38bdf8', '#2563eb'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom' } } }
                });
            }
    
            // 4. Site Type Chart
            const ctx4 = document.getElementById('siteTypeChart')?.getContext('2d');
            if(ctx4) {
                new Chart(ctx4, {
                    type: 'doughnut',
                    data: {
                        labels: ['ZIP File', 'GitHub'],
                        datasets: [{
                            data: [{{ $siteStats['file'] }}, {{ $siteStats['github'] }}],
                            backgroundColor: ['#f472b6', '#6366f1'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom' } } }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleRevenue');
            const revenueText = document.getElementById('revenueValue');
            const eyeIcon = document.getElementById('eyeIcon');
            
            // Ambil status dari LocalStorage (default: visible)
            let isHidden = localStorage.getItem('revenueHidden') === 'true';
    
            // Fungsi untuk Update Tampilan
            const updateDisplay = (hidden) => {
                if (hidden) {
                    revenueText.innerText = 'Rp ••••••••';
                    eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    revenueText.innerText = revenueText.getAttribute('data-full');
                    eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            };
    
            // Jalankan saat halaman pertama dimuat
            updateDisplay(isHidden);
    
            // Event Klik
            toggleBtn.addEventListener('click', function() {
                isHidden = !isHidden; // Switch status
                localStorage.setItem('revenueHidden', isHidden); // Simpan ke browser
                updateDisplay(isHidden);
            });
        });
    </script>
    @endpush
</x-admin-layout>