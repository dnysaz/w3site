<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
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
                Perbarui kunci <br><span class="text-blue-400 italic">akses Anda.</span>
            </h2>
            <p class="text-slate-400 text-sm max-w-xs">
                Gunakan kombinasi karakter yang kuat untuk memastikan keamanan infrastruktur Anda tetap terjaga.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
            <span>Account Security Protocol</span>
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white">
        
        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Reset Password</h1>
                <p class="text-slate-500 font-medium">Lengkapi formulir di bawah untuk membuat password baru.</p>
            </div>

            <form wire:submit="resetPassword" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Email</label>
                    <input wire:model="email" type="email" required readonly
                           class="w-full px-5 py-4 bg-slate-100 border border-slate-200 rounded-2xl font-semibold text-slate-500 cursor-not-allowed outline-none">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Password Baru</label>
                    <input wire:model="password" type="password" required autofocus autocomplete="new-password"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Konfirmasi Password Baru</label>
                    <input wire:model="password_confirmation" type="password" required autocomplete="new-password"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>