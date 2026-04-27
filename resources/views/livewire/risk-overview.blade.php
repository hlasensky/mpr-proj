<div>
    @php
        use App\Enums\RiskLevelCategoryEnum;

        // 5×5 zone matrix — indexed [likelihood][impact]
        $zones = [
            5 => [1 => RiskLevelCategoryEnum::Medium, 2 => RiskLevelCategoryEnum::High,   3 => RiskLevelCategoryEnum::Danger,  4 => RiskLevelCategoryEnum::Extreme, 5 => RiskLevelCategoryEnum::Extreme],
            4 => [1 => RiskLevelCategoryEnum::Medium, 2 => RiskLevelCategoryEnum::High,   3 => RiskLevelCategoryEnum::High,    4 => RiskLevelCategoryEnum::Danger,  5 => RiskLevelCategoryEnum::Extreme],
            3 => [1 => RiskLevelCategoryEnum::Low,    2 => RiskLevelCategoryEnum::Medium, 3 => RiskLevelCategoryEnum::High,    4 => RiskLevelCategoryEnum::High,    5 => RiskLevelCategoryEnum::Danger],
            2 => [1 => RiskLevelCategoryEnum::Low,    2 => RiskLevelCategoryEnum::Low,    3 => RiskLevelCategoryEnum::Medium,  4 => RiskLevelCategoryEnum::High,    5 => RiskLevelCategoryEnum::High],
            1 => [1 => RiskLevelCategoryEnum::Low,    2 => RiskLevelCategoryEnum::Low,    3 => RiskLevelCategoryEnum::Low,     4 => RiskLevelCategoryEnum::Medium,  5 => RiskLevelCategoryEnum::Medium],
        ];

        // Group risks by [likelihood][impact]
        $byCell = [];
        foreach ($risks as $r) {
            $byCell[$r->likelihood][$r->impact][] = $r;
        }
    @endphp

    {{-- Project header --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ $project->name }}</flux:heading>
            @if ($project->description)
                <flux:text variant="subtle" class="mt-1 max-w-prose">{{ $project->description }}</flux:text>
            @endif
            @if ($project->start_date || $project->end_date)
                <div class="mt-2 flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400">
                    <flux:icon.calendar class="size-4" />
                    <span>{{ $project->start_date?->format('d.m.Y') ?? '—' }} → {{ $project->end_date?->format('d.m.Y') ?? '—' }}</span>
                </div>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button size="sm" variant="primary" icon="plus"
                :href="route('risk.editor', $project->id)" wire:navigate>
                Přidat riziko
            </flux:button>
            <flux:button size="sm" variant="ghost" icon="pencil"
                :href="route('project.editor', $project->id)" wire:navigate>
                Upravit projekt
            </flux:button>
            <flux:button size="sm" variant="danger" icon="trash"
                wire:click="deleteProject"
                wire:confirm="Smazat projekt {{ $project->name }}? Všechna rizika budou také smazána.">
                Smazat projekt
            </flux:button>
        </div>
    </div>

    {{-- Risk matrix --}}
    <div class="mb-8">
        <flux:heading size="lg" level="2" class="mb-4">Matice rizik</flux:heading>

        <div class="flex gap-3">
            {{-- Y-axis label --}}
            <div class="flex items-center justify-center">
                <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 [writing-mode:vertical-rl] rotate-180">
                    Pravděpodobnost →
                </span>
            </div>

            {{-- Matrix + X-axis --}}
            <div class="flex-1 overflow-x-auto">
                {{-- Grid: col 0 = likelihood labels, cols 1-5 = impact cells --}}
                <div class="grid min-w-105" style="grid-template-columns: 2rem repeat(5, 1fr)">

                    {{-- 5 rows, likelihood 5 → 1 --}}
                    @foreach ([5, 4, 3, 2, 1] as $lik)
                        <div class="flex items-center justify-center text-xs font-medium text-zinc-500 dark:text-zinc-400 pr-1">
                            {{ $lik }}
                        </div>
                        @foreach ([1, 2, 3, 4, 5] as $imp)
                            @php
                                $zone = $zones[$lik][$imp];
                                $cellRisks = $byCell[$lik][$imp] ?? [];
                            @endphp
                            <div class="min-h-16 border border-white/20 p-1 {{ $zone->cellClass() }}">
                                @foreach ($cellRisks as $risk)
                                    <span class="mb-0.5 flex items-center gap-1 rounded px-1.5 py-0.5 text-xs leading-tight {{ $zone->chipClass() }}">
                                        <span class="truncate">{{ $risk->name }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @endforeach
                    @endforeach

                    {{-- Impact axis labels row --}}
                    <div></div>
                    @foreach ([1, 2, 3, 4, 5] as $imp)
                        <div class="pt-1 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $imp }}</div>
                    @endforeach
                </div>

                <div class="mt-1 pl-8 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    → Dopad
                </div>

                {{-- Legend --}}
                <div class="mt-4 flex flex-wrap gap-3 pl-8">
                    @foreach (RiskLevelCategoryEnum::cases() as $cat)
                        <div class="flex items-center gap-1.5">
                            <span class="size-3 rounded-sm {{ $cat->cellClass() }} border border-white/20"></span>
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $cat->label() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Risk list --}}
    <flux:heading size="lg" level="2" class="mb-3">Seznam rizik</flux:heading>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Název</flux:table.column>
            <flux:table.column>Dopad</flux:table.column>
            <flux:table.column>Pravděpodobnost</flux:table.column>
            <flux:table.column>Skóre</flux:table.column>
            <flux:table.column>Kategorie</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($risks as $risk)
                <flux:table.row :key="$risk->id">
                    <flux:table.cell variant="strong">{{ $risk->name }}</flux:table.cell>
                    <flux:table.cell>{{ $risk->impact }}</flux:table.cell>
                    <flux:table.cell>{{ $risk->likelihood }}</flux:table.cell>
                    <flux:table.cell variant="strong">{{ $risk->score() }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" inset="top bottom" :color="$risk->riskCategory()->fluxColor()">
                            {{ $risk->riskCategory()->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:button size="sm" variant="ghost" icon="pencil" inset="top bottom"
                                :href="route('risk.editor', [$project->id, $risk->id])" wire:navigate>
                                Upravit
                            </flux:button>
                            <flux:button size="sm" variant="danger" icon="trash" inset="top bottom"
                                wire:click="deleteRisk({{ $risk->id }})"
                                wire:confirm="Smazat riziko {{ $risk->name }}?">
                                Smazat
                            </flux:button>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center">
                        <flux:text variant="subtle">Žádná rizika.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
