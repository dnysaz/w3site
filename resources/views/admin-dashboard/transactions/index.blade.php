<x-admin-layout>
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Financial <span class="text-blue-600">Reports</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium">Pantau arus kas dan status pembayaran pengguna.</p>
        </div>
    </div>

    {{-- Stats Cards & Chart Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Revenue Card --}}
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl transition-transform group-hover:scale-110">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Revenue</p>
                <h3 class="text-2xl font-black text-slate-900">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            </div>
            <i class="fa-solid fa-money-bill-trend-up absolute -right-4 -bottom-4 text-slate-50 text-6xl rotate-12"></i>
        </div>

        {{-- Revenue Trend Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-blue-600">Revenue Trend (Last 7 Days)</p>
            </div>
            <div class="h-32">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                <i class="fa-solid fa-receipt text-blue-600"></i> Transaction History
            </h3>
            
            {{-- TOMBOL EXPORT --}}
            <a href="{{ route('admin.transactions.export') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md active:scale-95">
                <i class="fa-solid fa-file-excel text-sm"></i>
                Export Excel
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Order ID / User</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center border bg-blue-50 text-blue-500 border-blue-100">
                                    <i class="fa-solid fa-hashtag text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 tracking-tight">{{ $trx->order_id }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-tighter">{{ $trx->user->name ?? 'Unknown User' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <p class="text-sm font-black text-slate-800 italic">Rp{{ number_format($trx->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @php
                                $statusClasses = [
                                    'settlement' => 'text-blue-600 bg-blue-50',
                                    'capture'    => 'text-blue-600 bg-blue-50',
                                    'pending'    => 'text-amber-600 bg-amber-50',
                                    'expire'     => 'text-slate-400 bg-slate-100',
                                    'deny'       => 'text-red-600 bg-red-50',
                                ];
                                $class = $statusClasses[$trx->transaction_status] ?? 'text-slate-500 bg-slate-100';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $class }}">
                                {{ $trx->transaction_status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                {{ $trx->created_at->format('d M Y') }}
                            </p>
                            <p class="text-[10px] text-slate-300 font-medium">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">No Transactions Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="p-8 border-t border-slate-50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 150);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } } },
                    y: { display: false, beginAtZero: true }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>