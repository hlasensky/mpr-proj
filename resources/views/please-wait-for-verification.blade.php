<x-layouts::auth :title="__('Čekání na ověření')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-center gap-2 text-center">
            <flux:icon.clock class="size-12 text-zinc-400 dark:text-zinc-500" />
            <flux:heading size="lg">Účet čeká na ověření</flux:heading>
            <flux:text variant="subtle">
                Váš účet byl zaregistrován, ale zatím nebyl ověřen administrátorem.
                Jakmile vám bude přidělena role, získáte přístup do systému.
            </flux:text>
        </div>

        @auth
            <flux:text class="text-center text-sm" variant="subtle">
                Přihlášen jako <strong>{{ auth()->user()->email }}</strong>
            </flux:text>
        @endauth

        <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
            @csrf
            <flux:button variant="ghost" type="submit" class="cursor-pointer">
                Odhlásit se
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
