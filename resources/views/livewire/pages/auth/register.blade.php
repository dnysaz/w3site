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

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-10">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">Sign up</h1>
                <p class="text-[14px] text-zinc-500 mt-1">to create your w3site.id account</p>
            </div>
        </div>

        <form wire:submit="register" class="space-y-6">
            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Full Name</label>
                <input wire:model="name" type="text" required autofocus 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="Enter your name">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Email Address</label>
                <input wire:model="email" type="email" required 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="name@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Password</label>
                <input wire:model="password" type="password" required 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="Minimum 8 characters">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label class="block text-[12px] font-medium text-zinc-500 mb-2 uppercase tracking-wide">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" required 
                       class="w-full h-11 px-4 bg-white border border-zinc-200 rounded-lg text-[14px] font-medium outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" 
                       placeholder="Repeat password">
            </div>

            <button type="submit" class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                Create Account
            </button>

            <p class="text-center text-[14px] text-zinc-500 pt-6">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-black font-semibold hover:underline underline-offset-4" wire:navigate>Log in</a>
            </p>
        </form>
    </div>
</div>