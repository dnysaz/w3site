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

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-12">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">New Password</h1>
                <p class="text-[14px] text-zinc-500 mt-1">Choose a safe password for your account</p>
            </div>
        </div>

        <form wire:submit="resetPassword" class="space-y-6">
            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Email Address</label>
                <input wire:model="email" type="email" required readonly 
                       class="w-full h-11 px-4 bg-zinc-50 border border-zinc-200 rounded-lg text-[14px] font-medium text-zinc-500 outline-none">
            </div>

            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">New Password</label>
                <input wire:model="password" type="password" required autofocus 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" required 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="••••••••">
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                <span wire:loading.remove>Update Password</span>
                <span wire:loading>Updating...</span>
            </button>
        </form>
    </div>
</div>