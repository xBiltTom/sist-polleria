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

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        // Asignar automáticamente el rol de Cliente (id 6)
        $user->assignRole(6);

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-polleria-800 dark:text-polleria-dark-100">
            ¡Únete a nosotros! 🐔
        </h2>
        <p class="text-polleria-600 dark:text-polleria-dark-300 mt-1">
            Crea tu cuenta para comenzar
        </p>
    </div>

    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" class="text-polleria-700 dark:text-polleria-dark-200 font-medium" />
            <x-text-input wire:model="name" id="name"
                class="block mt-1 w-full rounded-xl border-polleria-300 dark:border-polleria-dark-600
                       focus:border-polleria-500 focus:ring-polleria-500
                       dark:focus:border-polleria-dark-400 dark:focus:ring-polleria-dark-400
                       dark:bg-polleria-dark-800 dark:text-white
                       transition-all duration-200"
                type="text" name="name" required autofocus autocomplete="name"
                placeholder="Tu nombre completo" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-polleria-700 dark:text-polleria-dark-200 font-medium" />
            <x-text-input wire:model="email" id="email"
                class="block mt-1 w-full rounded-xl border-polleria-300 dark:border-polleria-dark-600
                       focus:border-polleria-500 focus:ring-polleria-500
                       dark:focus:border-polleria-dark-400 dark:focus:ring-polleria-dark-400
                       dark:bg-polleria-dark-800 dark:text-white
                       transition-all duration-200"
                type="email" name="email" required autocomplete="username"
                placeholder="correo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-polleria-700 dark:text-polleria-dark-200 font-medium" />
            <x-text-input wire:model="password" id="password"
                class="block mt-1 w-full rounded-xl border-polleria-300 dark:border-polleria-dark-600
                       focus:border-polleria-500 focus:ring-polleria-500
                       dark:focus:border-polleria-dark-400 dark:focus:ring-polleria-dark-400
                       dark:bg-polleria-dark-800 dark:text-white
                       transition-all duration-200"
                type="password"
                name="password"
                required autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="text-polleria-700 dark:text-polleria-dark-200 font-medium" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation"
                class="block mt-1 w-full rounded-xl border-polleria-300 dark:border-polleria-dark-600
                       focus:border-polleria-500 focus:ring-polleria-500
                       dark:focus:border-polleria-dark-400 dark:focus:ring-polleria-dark-400
                       dark:bg-polleria-dark-800 dark:text-white
                       transition-all duration-200"
                type="password"
                name="password_confirmation" required autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center items-center py-3 px-4
                       bg-gradient-to-r from-polleria-500 to-polleria-600
                       hover:from-polleria-600 hover:to-polleria-700
                       dark:from-polleria-dark-500 dark:to-polleria-dark-600
                       dark:hover:from-polleria-dark-400 dark:hover:to-polleria-dark-500
                       text-white font-semibold rounded-xl shadow-lg
                       hover:shadow-xl transform hover:-translate-y-0.5
                       transition-all duration-200 focus:outline-none focus:ring-2
                       focus:ring-polleria-500 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                {{ __('Registrarme') }}
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-polleria-600 dark:text-polleria-dark-300 text-sm">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}" wire:navigate
                   class="font-semibold text-polleria-700 hover:text-polleria-800
                          dark:text-polleria-dark-200 dark:hover:text-polleria-dark-100
                          underline decoration-2 decoration-polleria-400
                          hover:decoration-polleria-600 transition-all duration-200">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </form>
</div>
