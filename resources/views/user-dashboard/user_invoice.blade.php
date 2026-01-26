<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->order_id }} - W3SITE.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-shadow-none { shadow: none !important; border: 1px solid #f1f5f9 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-12 px-4 selection:bg-blue-100">

    {{-- Container Utama --}}
    <div class="max-w-2xl mx-auto bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 overflow-hidden relative print-shadow-none">
        
        {{-- Floating Action (No Print) --}}
        <div class="absolute top-8 right-8 no-print flex gap-2">
            <button onclick="window.print()" class="flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-white px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl transition-all active:scale-95">
                <i class="fa-solid fa-print"></i> Print Struk
            </button>
        </div>

        {{-- Top Header - Branding --}}
        <div class="bg-slate-900 p-12 text-white relative overflow-hidden">
            {{-- Background Accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-cube text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-[1000] tracking-tighter">w3site<span class="text-blue-500">.id</span></h1>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em]">Official Digital Receipt</p>
            </div>
            
            <div class="absolute bottom-8 right-12 text-right opacity-20">
                <i class="fa-solid fa-file-invoice-dollar text-7xl"></i>
            </div>
        </div>

        <div class="p-12">
            {{-- User & Transaction Info --}}
            <div class="flex flex-col md:flex-row justify-between gap-10 mb-12">
                <div>
                    <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Diterbitkan Untuk:</h4>
                    <p class="text-lg font-[1000] text-slate-900 tracking-tight">{{ $transaction->user->name }}</p>
                    <p class="text-xs text-slate-500 font-medium">{{ $transaction->user->email }}</p>
                </div>
                <div class="md:text-right">
                    <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Rincian Transaksi:</h4>
                    <p class="text-sm font-black text-slate-900 uppercase tracking-tight">ID: #{{ $transaction->order_id }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-1 italic">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl border border-slate-100 overflow-hidden mb-10">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">Item Description</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-slate-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr>
                            <td class="px-6 py-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $transaction->package_name }}</p>
                                        <p class="text-[10px] text-blue-500 font-bold mt-1 uppercase tracking-wider">Masa Aktif: 1 Bulan</p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1 italic italic">Valid Until: {{ $transaction->expired_at->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8 text-right">
                                <span class="text-lg font-[1000] text-slate-900 tracking-tighter italic">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                {{-- Total Summary --}}
                <div class="bg-slate-900 p-8 flex justify-between items-center text-white">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total Pembayaran</p>
                        <p class="text-[9px] text-blue-400 font-bold italic mt-1 uppercase leading-none">Status: {{ strtoupper($transaction->transaction_status) }} (LUNAS)</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-[1000] tracking-tighter">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Meta Payment Info --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-widest">
                        <i class="fa-solid fa-credit-card mr-1"></i> {{ str_replace('_', ' ', $transaction->payment_type) }}
                    </div>
                    <div class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">
                        <i class="fa-solid fa-circle-check mr-1"></i> Verified
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-[9px] font-bold text-slate-400 tracking-widest italic">Dokumen ini diterbitkan secara elektronik dan sah tanpa tanda tangan.</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-slate-50 p-8 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-3">w3site.id - Simple Web Hosting</p>
        </div>
    </div>

    {{-- Bottom Spacer for print --}}
    <div class="h-12 no-print"></div>

</body>
</html>