<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">
            {{ $project?->exists ? 'Upravit projekt' : 'Nový projekt' }}
        </flux:heading>
        <flux:text variant="subtle" class="mt-1">
            {{ $project?->exists ? "Úprava projektu {$project->name}" : 'Vytvoření nového projektu.' }}
        </flux:text>
    </div>

    <form wire:submit="save" class="max-w-lg space-y-6">
        <flux:field>
            <flux:label>Název projektu</flux:label>
            <flux:input wire:model="name" type="text" placeholder="Název projektu" required />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>Popis</flux:label>
            <flux:textarea wire:model="description" placeholder="Stručný popis projektu..." rows="3" />
            <flux:error name="description" />
        </flux:field>

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Datum zahájení</flux:label>
                <flux:input wire:model="startDate" type="date" />
                <flux:error name="startDate" />
            </flux:field>

            <flux:field>
                <flux:label>Datum ukončení</flux:label>
                <flux:input wire:model="endDate" type="date" />
                <flux:error name="endDate" />
            </flux:field>
        </div>

        <div class="flex items-center gap-3">
            <flux:button type="submit" variant="primary">Uložit</flux:button>
            <flux:button :href="route('dashboard')" wire:navigate variant="ghost">Zrušit</flux:button>
        </div>
    </form>
</div>
