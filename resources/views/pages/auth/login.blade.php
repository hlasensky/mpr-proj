<x-layouts::auth :title="__('Přihlášení')">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-xl font-semibold tracking-tight" style="color: var(--fg);">Přihlášení</h1>
            <p class="mt-1 text-sm" style="color: var(--fg-muted);">Zadejte e-mail a heslo pro přihlášení.</p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <flux:input
                name="email"
                label="E-mailová adresa"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="vas@email.cz"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    label="Heslo"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Vaše heslo"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 inset-e-0 text-sm" :href="route('password.request')" wire:navigate>
                        Zapomenuté heslo?
                    </flux:link>
                @endif
            </div>

            <flux:checkbox name="remember" label="Zapamatovat přihlášení" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full mt-1" data-test="login-button">
                Přihlásit se
            </flux:button>
        </form>

        @if (Route::has('register'))
            <p class="text-sm text-center" style="color: var(--fg-muted);">
                Nemáte účet?
                <flux:link :href="route('register')" wire:navigate>Registrovat se</flux:link>
            </p>
        @endif
    </div>
</x-layouts::auth>
