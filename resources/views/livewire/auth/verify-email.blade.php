<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Verifica tu correo</h1>
        <p class="text-slate-600 dark:text-slate-400">
            Por favor, verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <p class="text-center text-sm font-medium text-green-700 dark:text-green-400">
                Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
            </p>
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full !bg-gradient-to-r !from-sky-500 !to-indigo-600 !border-0 hover:!shadow-lg hover:!shadow-sky-500/25 transition-shadow">
            Reenviar correo de verificación
        </flux:button>

        <flux:button wire:click="logout" variant="ghost" class="w-full">
            Cerrar sesión
        </flux:button>
    </div>
</div>
