<x-admin-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 tracking-tighter">
            System <span class="text-blue-600">Overview</span>
        </h2>
        <p class="text-slate-500 text-[15px] font-bold mt-1">Monitoring platform data in real-time.</p>
    </div>

    {{-- 1. Statistik Utama (Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[12px] font-black text-slate-400 tracking-widest">Total Users</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ number_format($stats['total_users']) }}</h3>
            </div>
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[12px] font-black text-slate-400 tracking-widest">Total Sites</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ number_format($stats['total_sites']) }}</h3>
            </div>
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-globe text-2xl"></i>
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-all">
            <div>
                <p class="text-[12px] font-black text-slate-400 tracking-widest">Total Visits</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ number_format($stats['total_visits']) }}</h3>
            </div>
            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bolt-lightning text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- 2. BLOK BARU: SERVER HEALTH (Lebar Penuh) --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm mb-8 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-server text-xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 tracking-tighter text-lg">Server Health</h3>
                    <p class="text-slate-400 text-[10px] font-bold tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        System Online: {{ $serverStats['uptime'] }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 flex-1 md:ml-12">
                {{-- CPU Usage --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">CPU Load</span>
                        <span class="text-xs font-black text-slate-900">{{ $serverStats['cpu'] }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ $serverStats['cpu'] }}"></div>
                    </div>
                </div>

                {{-- RAM Usage --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RAM Usage</span>
                        <span class="text-xs font-black text-slate-900">{{ $serverStats['ram'] }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $serverStats['ram_p'] }}%"></div>
                    </div>
                </div>

                {{-- Disk Usage --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Storage</span>
                        <span class="text-xs font-black text-slate-900">{{ $serverStats['disk'] }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full transition-all duration-1000" style="width: {{ $serverStats['disk_p'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Hiasan Background --}}
        <i class="fa-solid fa-microchip absolute -right-4 -bottom-4 text-slate-50 text-8xl"></i>
    </div>

    {{-- 3. Grafik Utama --}}
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
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 text-center text-[13px] tracking-widest">User Package Distribution</h3>
            <div class="h-72">
                <canvas id="userPackageChart"></canvas>
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 mb-6 text-center text-[13px] tracking-widest">Website Type Distribution</h3>
            <div class="h-72">
                <canvas id="siteTypeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 5. Latest Activity --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h3 class="font-black text-slate-900 tracking-tighter text-lg">Latest Activity</h3>
            <span class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[11px] font-black tracking-widest">Live Updates</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 tracking-widest">Time</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 tracking-widest">Page / Subdomain</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 tracking-widest">Location</th>
                        <th class="px-8 py-4 text-[11px] font-black text-slate-400 tracking-widest">Browser</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($latest_visits as $visit)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <p class="text-[14px] font-bold text-slate-900">{{ $visit->created_at->diffForHumans() }}</p>
                                <p class="text-[11px] text-slate-400 font-medium">{{ $visit->created_at->format('H:i:s') }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-[13px] font-bold">{{ $visit->subdomain ?? 'Main Site' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-[14px] font-bold text-slate-900">{{ $visit->country ?? 'Unknown' }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $visit->city ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-brands fa-{{ strtolower($visit->browser) }} text-sm"></i>
                                    <span class="text-[13px] font-bold text-slate-900">{{ $visit->browser }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <p class="text-[15px] font-bold text-slate-400">No activity recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
    {{-- Removed toggle script as revenue card is gone --}}
    @endpush
</x-admin-layout>