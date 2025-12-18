<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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

<div class="flex flex-col gap-6">
    <div class="text-center">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center">
                <svg class="w-8 h-8 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Confirmar contraseña</h1>
        <p class="text-slate-600 dark:text-slate-400">
            Esta es un área segura. Por favor, confirma tu contraseña antes de continuar.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="flex flex-col gap-5">
        <!-- Password -->
        <flux:input
            wire:model="password"
            label="Contraseña"
            type="password"
            required
            autocomplete="current-password"
            placeholder="••••••••"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full !bg-gradient-to-r !from-sky-500 !to-indigo-600 !border-0 hover:!shadow-lg hover:!shadow-sky-500/25 transition-shadow">
            Confirmar
        </flux:button>
    </form>
</div>
