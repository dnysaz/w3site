<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-12">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">Reset Password</h1>
                <p class="text-[14px] text-zinc-500 mt-1">We'll send you a link to your email</p>
            </div>
        </div>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Email Address</label>
                <input wire:model="email" type="email" required autofocus 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="name@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                <span wire:loading.remove>Send Reset Link</span>
                <span wire:loading>Sending link...</span>
            </button>

            <div class="text-center pt-4">
                <a href="{{ route('login') }}" class="text-[14px] text-zinc-500 hover:text-black font-medium transition-colors" wire:navigate>
                    Back to log in
                </a>
            </div>
        </form>
    </div>
</div>