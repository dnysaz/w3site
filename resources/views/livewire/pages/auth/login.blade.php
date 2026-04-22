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

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-10">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">Log in</h1>
                <p class="text-[14px] text-zinc-500 mt-1">to continue to w3site.id</p>
            </div>
        </div>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Email Address</label>
                <input wire:model="form.email" type="email" required autofocus 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="name@example.com">
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-[12px] font-medium text-zinc-500 uppercase tracking-wide">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[12px] font-medium text-zinc-400 hover:text-black transition-colors">Forgot?</a>
                    @endif
                </div>
                <input wire:model="form.password" type="password" required 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <div class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-zinc-300 text-black focus:border-black focus:ring-black transition-all">
                <label for="remember" class="ml-2 text-[14px] text-zinc-600 cursor-pointer select-none">Remember me</label>
            </div>

            <button type="submit" class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                Log in
            </button>

            <div class="relative py-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-100"></div>
                </div>
                <div class="relative flex justify-center text-[12px] uppercase">
                    <span class="bg-white px-2 text-zinc-400">or</span>
                </div>
            </div>

            <a href="{{ route('login.google') }}" 
               class="flex items-center justify-center gap-3 w-full h-11 bg-white border border-zinc-200 rounded-xl text-[14px] font-medium text-zinc-700 hover:bg-zinc-50 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <p class="text-center text-[14px] text-zinc-500 pt-4">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-black font-semibold hover:underline underline-offset-4">Sign up</a>
            </p>
        </form>
    </div>
</div>