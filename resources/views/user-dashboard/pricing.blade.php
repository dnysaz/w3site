<x-user-layout>
    <div x-data="{ currentPackage: {{ auth()->user()->package }} }" class="pb-20">
        
        <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-1">Status Langganan</p>
                <h1 class="text-4xl md:text-5xl font-[1000] text-slate-900 tracking-tighter leading-none">
                    Paket <span class="text-blue-600">{{ auth()->user()->package_name }}</span>
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-3">
                    Pilih paket di bawah ini untuk melakukan upgrade fitur dan kapasitas.
                </p>
            </div>
        
            @if(auth()->user()->package > 0)
            <div class="flex items-center gap-3 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                <i class="fa-solid fa-circle-check"></i>
                <span class="text-[11px] font-[900] uppercase tracking-widest">Langganan Aktif</span>
            </div>
            @endif
        </header>

        {{-- Pricing Grid Section --}}
        <section class="max-w-7xl mx-auto border-t border-slate-100 pt-16">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-[900] tracking-tight mb-4 text-slate-900">Pilih paket sesuai kebutuhan</h2>
                <p class="text-slate-500 font-medium text-lg">Mulai gratis untuk belajar, upgrade untuk skala lebih besar!</p>
            </div>
        
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            
                {{-- Starter Pack --}}
                <div class="group bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col hover:border-blue-500/30 transition-all duration-500">
                    <div class="mb-8">
                        <h3 class="text-slate-400 uppercase tracking-[0.3em] text-[10px] font-[1000] mb-3">Starter Pack</h3>
                        <div class="text-4xl text-slate-900 font-[1000] tracking-tighter">Rp 0<span class="text-slate-300 text-sm font-black uppercase ml-1">/ Bln</span></div>
                    </div>
                    
                    <ul class="space-y-4 mb-10 flex-1 text-[11px] text-slate-500 font-black uppercase tracking-tight">
                        <li class="flex items-center gap-3">✅ <span class="text-slate-900">256Mb</span> SSD Storage</li>
                        <li class="flex items-center gap-3">✅ <span class="text-slate-900">2 Sites</span></li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span>10 Shortlinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span>10 Biolinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> SSL Certificate</li>
                        <li class="flex items-center gap-3 text-blue-600"><span class="text-amber-400">✅</span>✨ 10 AI Gen Hits</li>
                        <li class="flex items-center gap-3">❌ <span class="line-through">AI SWOT Analysis</span></li>
                        <li class="flex items-center gap-3 ">❌ <span class="line-through">AI SEO Navbar</span></li>
                        <li class="flex items-center gap-3 ">❌ <span class="line-through">AI SEO Blog Content</span></li>
                    </ul>
        
                    <form action="{{ route('pricing.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_id" value="0">
                        <button type="submit" 
                            :disabled="currentPackage == 0"
                            class="w-full py-5 border-2 border-slate-100 rounded-[2rem] font-black text-[10px] uppercase tracking-widest text-slate-400 group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all text-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="currentPackage == 0 ? 'Paket Saat Ini' : 'Mulai Gratis'"></span>
                        </button>
                    </form>
                </div>
        
                {{-- Growth Pack --}}
                <div class="bg-white p-8 rounded-[3rem] border-2 border-blue-600 shadow-2xl shadow-blue-900/10 flex flex-col relative md:scale-105 z-10 transition-transform">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-6 py-2 rounded-full text-[9px] font-[1000] uppercase tracking-[0.2em] shadow-xl shadow-blue-200">Terlaris</div>
                    
                    <div class="mb-8 pt-2">
                        <h3 class="text-blue-600 uppercase tracking-[0.3em] text-[10px] font-[1000] mb-3">Growth Pack</h3>
                        <div class="flex items-baseline gap-1 font-[1000] text-slate-900 tracking-tighter">
                            <span class="text-4xl">Rp 29K</span>
                            <span class="text-slate-400 text-xs font-black uppercase tracking-widest">/Bln</span>
                        </div>
                    </div>
        
                    <ul class="space-y-4 mb-10 flex-1 text-[11px] text-slate-600 font-black uppercase tracking-tight">
                        <li class="flex items-center gap-3">✅ <span class="text-slate-900 font-[1000]">512Mb</span> SSD Storage</li>
                        <li class="flex items-center gap-3">✅ <span class="text-slate-900 font-[1000]">10 Sites</span></li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> 100 Shortlinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> 100 Biolinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> SSL Certificate</li>
                        <li class="flex items-center gap-3 text-blue-600"> <span class="text-amber-400">✅</span>✨ 100 AI Gen Hits</li>
                        <li class="flex items-center gap-3">❌ <span class="line-through">AI SWOT Analysis</span></li>
                        <li class="flex items-center gap-3">❌ <span class="line-through">AI SEO Navbar</span></li>
                        <li class="flex items-center gap-3">❌ <span class="line-through">AI SEO Blog Content</span></li>
                    </ul>
        
                    <button type="button"
                        onclick="payPackage(1)"
                        id="btn-pay-1"
                        :disabled="currentPackage == 1"
                        class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 shadow-xl shadow-blue-200 transition-all text-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="text-1" x-text="currentPackage == 1 ? 'Paket Saat Ini' : 'Pilih Pemula'"></span>
                    </button>
                </div>
        
                {{-- Business Pro --}}
                <div class="group bg-slate-900 p-8 rounded-[3rem] border border-slate-800 shadow-sm flex flex-col hover:border-amber-400 transition-all duration-500">
                    <div class="mb-8">
                        <h3 class="text-amber-400 uppercase tracking-[0.3em] text-[10px] font-[1000] mb-3">Business Pro</h3>
                        <div class="flex items-baseline gap-1 font-[1000] text-white tracking-tighter">
                            <span class="text-4xl">Rp 49K</span>
                            <span class="text-slate-500 text-xs font-black uppercase tracking-widest">/Bln</span>
                        </div>
                    </div>
        
                    <ul class="space-y-4 mb-10 flex-1 text-[11px] text-slate-400 font-black uppercase tracking-tight">
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> <span class="text-white font-[1000]">1024Mb</span> SSD Storage</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> <span class="text-white font-[1000]">20 Sites</span></li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> 1.000 Shortlinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> 1.000 Biolinks</li>
                        <li class="flex items-center gap-3"><span class="text-amber-400">✅</span> SSL Certificate</li>
                        <li class="flex items-center gap-3 font-bold text-amber-400"><span class="text-amber-400">✅</span>✨ 1.000 AI Gen Hits</li>
                        <li class="flex items-center gap-3 text-white"><span class="text-amber-400">✅</span> AI SWOT Analysis</li>
                        <li class="flex items-center gap-3 text-white"><span class="text-amber-400">✅</span> AI SEO Navbar</li>
                        <li class="flex items-center gap-3 text-white"><span class="text-amber-400">✅</span> AI SEO Blog Content</li>
                    </ul>
        
                    <button type="button"
                        onclick="payPackage(2)"
                        id="btn-pay-2"
                        :disabled="currentPackage == 2"
                        class="w-full py-5 bg-white/10 border border-white/10 rounded-[2rem] font-black text-[10px] uppercase tracking-widest text-white group-hover:bg-amber-400 group-hover:text-slate-900 group-hover:border-amber-400 transition-all text-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="text-2" x-text="currentPackage == 2 ? 'Paket Saat Ini' : 'Upgrade Ke Pro'"></span>
                    </button>
                </div>
        
            </div>
        </section>
    </div>

    {{-- Script Paymenku Integration --}}
    <script>
        function payPackage(packageId) {
            const btn = document.getElementById(`btn-pay-${packageId}`);
            const textSpan = document.getElementById(`text-${packageId}`);
            const originalContent = textSpan.innerHTML;
            
            // Loading State: Nonaktifkan tombol dan beri spinner
            btn.disabled = true;
            textSpan.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Memproses...';

            fetch("{{ route('pricing.select', [], true) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    package_id: packageId,
                    channel_code: 'qris2' // Menggunakan QRIS dengan fee terendah
                })
            })
            .then(async response => {
                const res = await response.json();
                if (!response.ok) throw new Error(res.error || 'Gagal menghubungi server Paymenku');
                return res;
            })
            .then(data => {
                // Sesuai dokumentasi Paymenku v1.0, kita mengarahkan ke pay_url
                if (data.pay_url) {
                    window.location.href = data.pay_url;
                } else if (data.status === 'success') {
                    // Penanganan jika paket gratis atau tanpa biaya
                    window.location.href = "{{ route('dashboard') }}?status=updated";
                }
            })
            .catch(error => {
                alert("Oops! " + error.message);
                btn.disabled = false;
                textSpan.innerHTML = originalContent;
            });
        }
    </script>
</x-user-layout>