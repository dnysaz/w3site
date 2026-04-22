<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Sesuai permintaan khusus Anda: Menghapus file sesi secara permanen.
     */
    public function sesi_hancurkan(): void
    {
        $sessionPath = storage_path('framework/sessions/' . Session::getId());
        
        if (File::exists($sessionPath)) {
            File::delete($sessionPath);
        }
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out.
     */
    public function logout(Logout $logout): void
    {
        $this->sesi_hancurkan();
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col items-center justify-center bg-white p-4">
    <div class="w-full max-w-[400px] border border-zinc-200 rounded-2xl p-8 md:p-10">
        <div class="flex flex-col items-center gap-6 mb-12">
            <a href="/" class="flex items-center justify-center border-2 border-black rounded-md w-8 h-8 font-bold text-[14px] tracking-tighter">w3</a>
            <div class="text-center">
                <h1 class="text-2xl font-semibold tracking-tight text-black">Verify Email</h1>
                <p class="text-[14px] text-zinc-500 mt-1">Check your inbox for a verification link</p>
            </div>
        </div>

        <div class="text-[14px] text-zinc-600 leading-relaxed mb-10 text-center">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-8 text-[13px] font-semibold text-green-600 text-center">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="space-y-4">
            <button wire:click="sendVerification" 
                    class="w-full h-11 bg-zinc-900 text-white rounded-xl text-[14px] font-semibold hover:bg-zinc-800 transition-all flex items-center justify-center">
                Resend Email
            </button>

            <div class="text-center pt-4">
                <button wire:click="logout" class="text-[14px] text-zinc-500 hover:text-black font-medium transition-colors">
                    Log Out
                </button>
            </div>
        </div>
    </div>
</div>