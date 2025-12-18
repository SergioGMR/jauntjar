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

new #[Layout('components.layouts.auth')] class extends Component {
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

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
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

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Restablecer contraseña</h1>
        <p class="text-slate-600 dark:text-slate-400">Introduce tu nueva contraseña</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="flex flex-col gap-5">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            label="Correo electrónico"
            type="email"
            required
            autocomplete="email"
            icon="envelope"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            label="Nueva contraseña"
            type="password"
            required
            autocomplete="new-password"
            placeholder="••••••••"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            label="Confirmar contraseña"
            type="password"
            required
            autocomplete="new-password"
            placeholder="••••••••"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full !bg-gradient-to-r !from-sky-500 !to-indigo-600 !border-0 hover:!shadow-lg hover:!shadow-sky-500/25 transition-shadow">
            Restablecer contraseña
        </flux:button>
    </form>
    
    <div class="text-center text-sm text-slate-600 dark:text-slate-400">
        ¿Recordaste tu contraseña?
        <flux:link :href="route('login')" wire:navigate class="font-medium">Volver al inicio</flux:link>
    </div>
</div>
