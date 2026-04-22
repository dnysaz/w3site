<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-12">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">Confirm Password</h1>
                <p class="text-[14px] text-zinc-500 mt-1">Please confirm your password to proceed</p>
            </div>
        </div>

        <form wire:submit="confirmPassword" class="space-y-6">
            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Password</label>
                <input wire:model="password" type="password" required autofocus 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                <span wire:loading.remove>Confirm</span>
                <span wire:loading>Verifying...</span>
            </button>

            <div class="text-center pt-4">
                <button type="button" onclick="history.back()" class="text-[14px] text-zinc-500 hover:text-black font-medium transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>