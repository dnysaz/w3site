<x-user-layout>
    <div class="">
        {{-- Header Section Tetap Sama --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="text-2xl font-[1000] text-slate-900 tracking-tight">Billing <span class="text-blue-600">History</span> </h1>
                <p class="text-xs text-slate-400 mt-2 font-black tracking-[0.2em]">Daftar transaksi dan masa aktif paket Anda</p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-[1000] uppercase tracking-widest text-slate-400">Order ID & Tanggal</th>
                            <th class="px-8 py-5 text-[10px] font-[1000] uppercase tracking-widest text-slate-400">Paket & Nominal</th>
                            <th class="px-8 py-5 text-[10px] font-[1000] uppercase tracking-widest text-slate-400">Status</th>
                            <th class="px-8 py-5 text-[10px] font-[1000] uppercase tracking-widest text-slate-400">Berlaku Hingga</th>
                            <th class="px-8 py-5 text-[10px] font-[1000] uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $trx)
                        <tr class="group hover:bg-slate-50/30 transition-all">
                            {{-- Order ID & Tanggal --}}
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-black text-slate-900 uppercase tracking-tight">#{{ $trx->order_id }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </td>

                            {{-- Paket & Nominal --}}
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-900 uppercase">{{ $trx->package_name }}</span>
                                    <span class="text-[10px] text-blue-600 font-black mt-1 italic">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-8 py-6">
                                @php
                                    $status = strtolower($trx->transaction_status);
                                    $color = match($status) {
                                        'settlement', 'capture' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'expire', 'cancel', 'deny' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-slate-50 text-slate-400 border-slate-100'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 border {{ $color }} text-[9px] font-[1000] uppercase rounded-lg tracking-widest">
                                    {{ $status == 'settlement' ? 'Lunas' : ($status == 'expire' ? 'Kadaluarsa' : $status) }}
                                </span>
                            </td>

                            {{-- Expiration Date --}}
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    @if(in_array($status, ['settlement', 'capture']))
                                        <span class="text-xs font-black text-slate-800 tracking-tighter">{{ $trx->expired_at->format('d M Y') }}</span>
                                        @php $daysLeft = (int) now()->diffInDays($trx->expired_at, false); @endphp
                                        @if($daysLeft < 0) <span class="text-[9px] font-black text-red-500 uppercase mt-1">Sesi Berakhir</span>
                                        @elseif($daysLeft == 0) <span class="text-[9px] font-black text-amber-500 uppercase mt-1 italic">Habis Hari Ini</span>
                                        @else <span class="text-[9px] font-black text-blue-400 uppercase mt-1 italic">Sisa {{ $daysLeft }} Hari</span> @endif
                                    @else
                                        <span class="text-[10px] font-black text-slate-200 uppercase tracking-widest">-</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-6 text-right">
                                @if(in_array($status, ['settlement', 'capture']))
                                    <a href="{{ route('billing.invoice', $trx->order_id) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-900 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-file-invoice opacity-50"></i> Invoice
                                    </a>
                                @elseif($status == 'pending' && $trx->snap_token)
                                    {{-- TOMBOL BARU: BAYAR SEKARANG --}}
                                    <button type="button" onclick="resumePayment('{{ $trx->snap_token }}')"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-md shadow-amber-100">
                                        <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                                    </button>
                                @else
                                    <span class="text-[9px] font-black text-slate-200 uppercase tracking-[0.2em] italic">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Belum ada riwayat pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script untuk resume payment --}}
    @php $snapUrl = config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'; @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        function resumePayment(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) { window.location.href = "{{ route('billing.index') }}?status=success"; },
                onPending: function(result) { window.location.href = "{{ route('billing.index') }}?status=pending"; },
                onError: function(result) { alert("Pembayaran Gagal!"); },
                onClose: function() { console.log('customer closed the popup without finishing the payment'); }
            });
        }
    </script>
</x-user-layout>