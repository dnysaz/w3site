<?php
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row min-h-[650px]">
    
    <div class="hidden md:flex md:w-[45%] bg-slate-900 p-12 flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 blur-[80px] rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold italic text-xl shadow-lg shadow-blue-900/50">
                    w3
                </div>
                <span class="text-2xl font-extrabold tracking-tighter text-white">
                    w3site<span class="text-blue-400">.id</span>
                </span>
            </a>
        </div>

        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold text-white leading-[1.2] mb-6 tracking-tight">
                Bangun kehadiran digital <span class="text-blue-400 italic">instan.</span>
            </h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-slate-400 text-sm">Deploy situs statis dalam hitungan detik.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-slate-400 text-sm">Infrastruktur serverless yang handal.</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
            <span>Powered by w3site Engine</span>
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white">
        
        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Masuk</h1>
                <p class="text-slate-500 font-medium">Selamat datang kembali di w3site.id</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Email</label>
                    <input wire:model="form.email" type="email" required autofocus 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">Lupa Password?</a>
                        @endif
                    </div>
                    <input wire:model="form.password" type="password" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                    <label for="remember" class="ml-2 text-sm font-medium text-slate-500 cursor-pointer select-none">Ingat saya</label>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                    <span>Masuk ke Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>

                <p class="text-center text-sm text-slate-500 font-medium pt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-extrabold hover:underline underline-offset-4">Daftar Sekarang</a>
                </p>
            </form>
        </div>
    </div>
</div>