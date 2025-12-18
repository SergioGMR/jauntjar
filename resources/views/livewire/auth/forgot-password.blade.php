<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">¿Olvidaste tu contraseña?</h1>
        <p class="text-slate-600 dark:text-slate-400">No te preocupes, te enviaremos un enlace para restablecerla</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-5">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            label="Correo electrónico"
            type="email"
            required
            autofocus
            placeholder="tu@email.com"
            icon="envelope"
        />

        <flux:button variant="primary" type="submit" class="w-full !bg-gradient-to-r !from-sky-500 !to-indigo-600 !border-0 hover:!shadow-lg hover:!shadow-sky-500/25 transition-shadow">
            Enviar enlace de recuperación
        </flux:button>
    </form>

    <div class="text-center text-sm text-slate-600 dark:text-slate-400">
        ¿Recordaste tu contraseña?
        <flux:link :href="route('login')" wire:navigate class="font-medium">Volver al inicio</flux:link>
    </div>
</div>
