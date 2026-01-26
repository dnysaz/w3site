<x-admin-layout>
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Shortlinks <span class="text-blue-600">Analytics</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium">Monitoring performa pemendek URL dan aktivitas klik.</p>
        </div>
    </div>

    {{-- Stats Cards & Chart Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Clicks Card --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl transition-transform group-hover:scale-110">
                <i class="fa-solid fa-arrow-pointer"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Clicks</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['total_clicks']) }} <span class="text-xs font-medium text-slate-400 uppercase">Klik</span></h3>
            </div>
            <i class="fa-solid fa-bolt absolute -right-4 -bottom-4 text-slate-50 text-6xl rotate-12"></i>
        </div>

        {{-- Growth Chart Card --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Usage Trend (Last 7 Days)</p>
            </div>
            <div class="h-32">
                <canvas id="shortlinkChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                <i class="fa-solid fa-link text-blue-600"></i> Active Shortlinks
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">URL Info / Owner</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Traffic</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($shortlinks as $link)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center border bg-blue-50 text-blue-500 border-blue-100">
                                    <i class="fa-solid fa-scissors text-xs"></i>
                                </div>
                                <div>
                                    {{-- Kolom disesuaikan dengan migrasi baru (slug & destination_url) --}}
                                    <p class="text-sm font-black text-slate-900 italic">https://w3site.id/{{ $link->slug }}</p>
                                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-tighter">By: {{ $link->user->name ?? 'System' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">
                                {{-- Kolom disesuaikan dengan migrasi baru (clicks) --}}
                                <i class="fa-solid fa-fire text-[8px]"></i> {{ number_format($link->clicks) }} CLICKS
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black {{ $link->is_active ? 'text-blue-600 bg-blue-50' : 'text-slate-400 bg-slate-100' }} px-3 py-1 rounded-full uppercase">
                                {{ $link->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ url('/' . $link->slug) }}" target="_blank" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-external-link text-xs"></i>
                                </a>
                                <form action="{{ route('admin.shortlinks.toggle', $link->id) }}" method="POST">
                                    @csrf
                                    <button class="w-9 h-9 rounded-xl {{ $link->is_active ? 'bg-red-50 text-red-500 hover:bg-red-500' : 'bg-blue-50 text-blue-600 hover:bg-blue-500' }} hover:text-white flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-power-off text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">No Shortlinks Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('shortlinkChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 150);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!}, 
            datasets: [{
                label: 'Shortlink Baru',
                data: {!! json_encode($chartData) !!}, 
                borderColor: '#2563eb', 
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    displayColors: false,
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } }
                },
                y: { display: false, beginAtZero: true }
            }
        }
    });
</script>
@endpush
</x-admin-layout>