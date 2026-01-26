<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // $this->redirect(route('dashboard', absolute: false), navigate: true);
        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div class="flex flex-col md:flex-row min-h-[700px]">
    
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
                Mulai perjalanan <br>digital Anda <span class="text-blue-400 italic">disini.</span>
            </h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3 text-slate-400">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-sm">Gratis subdomain untuk selamanya.</p>
                </div>
                <div class="flex items-start gap-3 text-slate-400">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-sm">Optimasi SEO otomatis untuk setiap situs.</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
            Revolusi Web Modern Indonesia
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white">
        
        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Daftar Akun</h1>
                <p class="text-slate-500 font-medium">Buat akun gratis untuk mulai membangun situs.</p>
            </div>

            <form wire:submit="register" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap</label>
                    <input wire:model="name" type="text" required autofocus 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="Masukkan nama Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Alamat Email</label>
                    <input wire:model="email" type="email" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Kata Sandi</label>
                    <input wire:model="password" type="password" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="Minimal 8 karakter">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Konfirmasi Sandi</label>
                    <input wire:model="password_confirmation" type="password" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="Ulangi kata sandi">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-blue-600 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        <span>Buat Akun Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>

                <p class="text-center text-sm text-slate-500 font-medium pt-4">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-extrabold hover:underline underline-offset-4" wire:navigate>Masuk</a>
                </p>
            </form>
        </div>
    </div>
</div>