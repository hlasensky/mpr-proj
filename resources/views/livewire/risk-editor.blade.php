<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">
            {{ $risk?->exists ? 'Upravit riziko' : 'Nové riziko' }}
        </flux:heading>
        <flux:text variant="subtle" class="mt-1">
            {{ $risk?->exists ? "Úprava rizika {$risk->name}" : 'Přidání nového rizika k projektu.' }}
        </flux:text>
    </div>

    <form wire:submit="save" class="max-w-lg space-y-6">
        <flux:field>
            <flux:label>Název rizika</flux:label>
            <flux:input wire:model="name" type="text" placeholder="Popis rizika" required />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>Dopad (Impact) <span class="text-zinc-400 text-sm font-normal">1 = minimální, 10 = katastrofální</span></flux:label>
            <flux:radio.group wire:model.number="impact" variant="segmented">
                @foreach (range(1, 10) as $val)
                    <flux:radio :value="$val" :label="(string) $val" />
                @endforeach
            </flux:radio.group>
            <flux:error name="impact" />
        </flux:field>

        <flux:field>
            <flux:label>Pravděpodobnost (Likelihood) <span class="text-zinc-400 text-sm font-normal">1 = vzácné, 10 = téměř jisté</span></flux:label>
            <flux:radio.group wire:model.number="likelihood" variant="segmented">
                @foreach (range(1, 10) as $val)
                    <flux:radio :value="$val" :label="(string) $val" />
                @endforeach
            </flux:radio.group>
            <flux:error name="likelihood" />
        </flux:field>

        <div class="flex items-center gap-3">
            <flux:button type="submit" variant="primary">Uložit</flux:button>
            <flux:button :href="url()->previous()" wire:navigate variant="ghost">Zrušit</flux:button>
        </div>
    </form>
</div>
