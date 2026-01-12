<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">¡Bienvenido!</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="form.email" id="email"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:border-polleria-500 focus:ring-polleria-500 dark:focus:border-polleria-dark-500 dark:focus:ring-polleria-dark-500"
                type="email" name="email" required autofocus autocomplete="username"
                placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="form.password" id="password"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:border-polleria-500 focus:ring-polleria-500 dark:focus:border-polleria-dark-500 dark:focus:ring-polleria-dark-500"
                type="password"
                name="password"
                required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 dark:border-gray-600 text-polleria-600 dark:text-polleria-dark-500 shadow-sm focus:ring-polleria-500 dark:focus:ring-polleria-dark-500 dark:bg-gray-700"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-polleria-600 dark:text-polleria-dark-400 hover:text-polleria-700 dark:hover:text-polleria-dark-300 hover:underline"
                   href="{{ route('password.request') }}" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 px-4 py-3 bg-gradient-to-r from-polleria-500 to-polleria-600 dark:from-polleria-dark-500 dark:to-polleria-dark-600 hover:from-polleria-600 hover:to-polleria-700 dark:hover:from-polleria-dark-600 dark:hover:to-polleria-dark-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('Iniciar Sesión') }}
            </button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                ¿No tienes una cuenta?
                <a href="{{ route('register') }}" wire:navigate
                   class="font-semibold text-polleria-600 dark:text-polleria-dark-400 hover:text-polleria-700 dark:hover:text-polleria-dark-300 hover:underline">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </form>
</div>
