<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight" style="color: var(--fg);">
                {{ $project?->exists ? 'Upravit projekt' : 'Nový projekt' }}
            </h1>
            <p class="mt-1 text-sm" style="color: var(--fg-muted);">
                {{ $project?->exists ? "Úprava projektu {$project->name}" : 'Vytvořte projekt a začněte evidovat rizika.' }}
            </p>
        </div>
        <div class="flex gap-2">
            <flux:button :href="route('dashboard')" wire:navigate variant="ghost">Zrušit</flux:button>
            <flux:button form="project-form" type="submit" variant="primary" icon="check">
                {{ $project?->exists ? 'Uložit změny' : 'Vytvořit projekt' }}
            </flux:button>
        </div>
    </div>

    <form id="project-form" wire:submit="save">
        <div class="grid gap-5 lg:grid-cols-3" style="max-width: 900px;">

            {{-- Main fields --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="rounded-(--radius) border p-5" style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
                    <div class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: var(--fg-subtle);">Základní údaje</div>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Název projektu</flux:label>
                            <flux:input wire:model="name" type="text" placeholder="Např. Migrace ERP systému" required />
                            <flux:error name="name" />
                        </flux:field>

                        <flux:field>
                            <flux:label>
                                Popis
                                <span class="font-normal" style="color: var(--fg-subtle);">(volitelné)</span>
                            </flux:label>
                            <flux:textarea wire:model="description" placeholder="Stručný popis projektu, cílů a kontextu…" rows="4" />
                            <flux:error name="description" />
                        </flux:field>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Datum zahájení</flux:label>
                                <flux:input wire:model="startDate" type="date" />
                                <flux:error name="startDate" />
                            </flux:field>
                            <flux:field>
                                <flux:label>
                                    Datum ukončení
                                    <span class="font-normal" style="color: var(--fg-subtle);">(volitelné)</span>
                                </flux:label>
                                <flux:input wire:model="endDate" type="date" />
                                <flux:error name="endDate" />
                            </flux:field>
                        </div>

                        @if ($project?->exists && auth()->user()?->role === \App\Enums\RoleEnum::Admin)
                            <flux:field>
                                <flux:label>Vlastník projektu</flux:label>
                                <flux:select wire:model="ownerId">
                                    @foreach ($managers as $manager)
                                        <flux:select.option value="{{ $manager->id }}">
                                            {{ $manager->name }} ({{ $manager->email }})
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="ownerId" />
                            </flux:field>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" icon="check">
                        {{ $project?->exists ? 'Uložit změny' : 'Vytvořit projekt' }}
                    </flux:button>
                    <flux:button :href="route('dashboard')" wire:navigate variant="ghost">Zrušit</flux:button>
                </div>
            </div>

            {{-- Sidebar tips --}}
            <div class="space-y-4">
                <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border);">
                    <div class="text-xs font-semibold mb-3" style="color: var(--fg);">Tipy</div>
                    <ul class="space-y-2 text-xs leading-relaxed" style="color: var(--fg-muted);">
                        <li class="flex gap-2">
                            <span style="color: var(--fg-subtle);">·</span>
                            Začněte krátkým popisem (1–2 věty).
                        </li>
                        <li class="flex gap-2">
                            <span style="color: var(--fg-subtle);">·</span>
                            Rizika přidávejte postupně — nemusí být všechna hned.
                        </li>
                        <li class="flex gap-2">
                            <span style="color: var(--fg-subtle);">·</span>
                            Skóre rizika = Dopad × Pravděpodobnost (max 100).
                        </li>
                        <li class="flex gap-2">
                            <span style="color: var(--fg-subtle);">·</span>
                            Datum ukončení lze doplnit později.
                        </li>
                    </ul>
                </div>

                <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border);">
                    <div class="text-xs font-semibold mb-2" style="color: var(--fg);">Kategorie rizik</div>
                    <div class="space-y-1.5">
                        @foreach ([
                            [1, '1–6',    'Nepravděpodobné'],
                            [2, '7–10',   'Málo pravděpodobné'],
                            [3, '11–21',  'Možné'],
                            [4, '22–36',  'Pravděpodobné'],
                            [5, '37–100', 'Velmi pravděpodobné'],
                        ] as [$b, $range, $lbl])
                            <div class="flex items-center gap-2 text-xs" style="color: var(--fg-muted);">
                                <span class="size-2 rounded-full shrink-0" style="background: var(--risk-{{ $b }});"></span>
                                <span class="font-mono" style="color: var(--fg-subtle);">{{ $range }}</span>
                                <span>{{ $lbl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
