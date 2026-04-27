<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">
            {{ $user?->exists ? 'Upravit uživatele' : 'Nový uživatel' }}
        </flux:heading>
        <flux:text variant="subtle" class="mt-1">
            {{ $user?->exists ? 'Úprava účtu ' . $user->name : 'Vytvoření nového uživatelského účtu.' }}
        </flux:text>
    </div>

    <form wire:submit="save" class="max-w-lg space-y-6">
        <flux:field>
            <flux:label>Jméno</flux:label>
            <flux:input wire:model="name" type="text" placeholder="Celé jméno" required />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>E-mail</flux:label>
            <flux:input wire:model="email" type="email" placeholder="email@example.com" required />
            <flux:error name="email" />
        </flux:field>

        <flux:field>
            <flux:label>Role</flux:label>
            <flux:radio.group wire:model="role" variant="segmented">
                @foreach (\App\Enums\RoleEnum::cases() as $roleOption)
                    <flux:radio :value="$roleOption->value" :label="$roleOption->label()" />
                @endforeach
            </flux:radio.group>
            <flux:error name="role" />
        </flux:field>

        <div class="flex items-center gap-3">
            <flux:button type="submit" variant="primary">Uložit</flux:button>
            <flux:button :href="route('user.overview')" wire:navigate variant="ghost">Zrušit</flux:button>
        </div>
    </form>
</div>
