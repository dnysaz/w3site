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
        
        // Pastikan file ada sebelum dihapus
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
        // Panggil fungsi penghancur file sesi sebelum logout standar
        $this->sesi_hancurkan();
        
        $logout();
        $this->redirect('/', navigate: true);
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
                Verifikasi <br><span class="text-blue-400 italic">Identitas Anda.</span>
            </h2>
            <div class="space-y-4 text-slate-400">
                <p class="text-sm">Hanya satu langkah lagi untuk mengaktifkan console infrastruktur Anda.</p>
                <div class="flex items-center gap-2 text-xs font-bold text-blue-400 uppercase tracking-widest">
                    <span class="w-8 h-[1px] bg-blue-400"></span>
                    Waiting for Confirmation
                </div>
            </div>
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white text-center md:text-left">
        <div class="max-w-md mx-auto w-full">
            <div class="mb-8">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto md:mx-0 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-4">Cek Email Anda</h1>
                <p class="text-slate-500 font-medium leading-relaxed">
                    Terima kasih telah mendaftar! Silakan klik link verifikasi yang baru saja kami kirimkan ke email Anda untuk mulai membangun situs di <strong>w3site.id</strong>.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl text-sm font-bold text-green-700">
                    Link verifikasi baru telah dikirim ke alamat email Anda.
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <button wire:click="sendVerification" 
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all">
                    Kirim Ulang Email Verifikasi
                </button>

                <button wire:click="logout" 
                        class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-widest">
                    Log Out
                </button>
            </div>
        </div>
    </div>
</div>