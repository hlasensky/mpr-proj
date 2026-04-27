<div>
    @php
        use App\Enums\RiskLevelCategoryEnum;

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
                    <span>{{ $project->start_date?->format('d.m.Y') ?? '—' }} →
                        {{ $project->end_date?->format('d.m.Y') ?? '—' }}</span>
                </div>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button size="sm" variant="primary" icon="plus" :href="route('risk.editor', $project->id)"
                wire:navigate>
                Přidat riziko
            </flux:button>
            <flux:button size="sm" variant="ghost" icon="pencil" :href="route('project.editor', $project->id)"
                wire:navigate>
                Upravit projekt
            </flux:button>
            <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteProject"
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

            {{-- Matrix + axes --}}
            <div class="flex-1 overflow-x-auto">
                {{--
                    Single CSS grid with explicit placement:
                    - Col 1 (5.5rem): y-axis category labels, each spanning 2 rows
                    - Cols 2–11: 10 data columns
                    - Row 1 (auto): x-axis category labels, each spanning 2 cols
                    - Rows 2–11: 10 data rows (likelihoods 10→1)
                --}}
                @php
                    $impCategories = [
                        ['label' => 'Nízké',     'col' => '2/4'],
                        ['label' => 'Střední',    'col' => '4/6'],
                        ['label' => 'Vysoké',     'col' => '6/8'],
                        ['label' => 'Nebezpečné', 'col' => '8/10'],
                        ['label' => 'Extrémní',   'col' => '10/12'],
                    ];
                    $likCategories = [
                        ['label' => 'Extrémní',   'row' => '2/4'],
                        ['label' => 'Nebezpečné', 'row' => '4/6'],
                        ['label' => 'Vysoké',     'row' => '6/8'],
                        ['label' => 'Střední',    'row' => '8/10'],
                        ['label' => 'Nízké',      'row' => '10/12'],
                    ];
                @endphp

                <div class="grid min-w-160" style="grid-template-columns: 5.5rem repeat(10, 1fr)">

                    {{-- Corner spacer --}}
                    <div style="grid-row: 1; grid-column: 1"></div>

                    {{-- X-axis: 5 category labels, each spanning 2 columns --}}
                    @foreach ($impCategories as $cat)
                        <div style="grid-row: 1; grid-column: {{ $cat['col'] }}"
                             class="pb-1 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                            {{ $cat['label'] }}
                        </div>
                    @endforeach

                    {{-- Y-axis: 5 category labels, each spanning 2 rows --}}
                    @foreach ($likCategories as $cat)
                        <div style="grid-row: {{ $cat['row'] }}; grid-column: 1"
                             class="flex items-center justify-end pr-2 text-right text-xs font-semibold leading-tight text-zinc-600 dark:text-zinc-300">
                            {{ $cat['label'] }}
                        </div>
                    @endforeach

                    {{-- Data cells (likelihoods 10→1, impacts 1→10, explicit grid placement) --}}
                    @foreach ([10, 9, 8, 7, 6, 5, 4, 3, 2, 1] as $rowIdx => $lik)
                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] as $colIdx => $imp)
                            @php
                                $gridRow = $rowIdx + 2;
                                $gridCol = $colIdx + 2;
                                $score = $lik * $imp;
                                $zone = RiskLevelCategoryEnum::fromScore($score);
                                $cellRisks = $byCell[$lik][$imp] ?? [];
                                $shown = array_slice($cellRisks, 0, 2);
                                $extra = count($cellRisks) - count($shown);
                            @endphp
                            <div style="grid-row: {{ $gridRow }}; grid-column: {{ $gridCol }}"
                                 class="min-h-12 overflow-hidden border border-white/10 p-1 {{ RiskLevelCategoryEnum::gradientCellClass($score) }}">
                                @foreach ($shown as $risk)
                                    <flux:button size="sm" variant="ghost" aria-label="Upravit"
                                        :href="route('risk.editor', [$project->id, $risk->id])" wire:navigate>
                                        <span class="mb-0.5 flex items-center gap-1 rounded px-1.5 py-0.5 text-xs leading-tight {{ RiskLevelCategoryEnum::gradientChipClass($score) }}">
                                            <span class="truncate">{{ $risk->name }}</span>
                                        </span>
                                    </flux:button>
                                @endforeach
                                @if ($extra > 0)
                                    <span class="mt-0.5 block text-center text-xs font-semibold text-white/70">
                                        +{{ $extra }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @endforeach

                </div>

                <div class="mt-1 pl-22 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    → Dopad
                </div>

                {{-- Legend: continuous gradient bar with 5 category labels --}}
                <div class="mt-4 pl-22">
                    <div class="flex h-4 overflow-hidden rounded-sm border border-white/20">
                        <div class="flex-1 bg-green-300 dark:bg-green-800/50"></div>
                        <div class="flex-1 bg-green-500 dark:bg-green-700/60"></div>
                        <div class="flex-1 bg-lime-300 dark:bg-lime-800/50"></div>
                        <div class="flex-1 bg-yellow-300 dark:bg-yellow-700/50"></div>
                        <div class="flex-1 bg-yellow-500 dark:bg-yellow-600/60"></div>
                        <div class="flex-1 bg-orange-400 dark:bg-orange-700/60"></div>
                        <div class="flex-1 bg-orange-600 dark:bg-orange-700/70"></div>
                        <div class="flex-1 bg-red-500 dark:bg-red-700/70"></div>
                        <div class="flex-1 bg-red-700 dark:bg-red-800/80"></div>
                        <div class="flex-1 bg-red-900 dark:bg-red-950/90"></div>
                    </div>
                    <div class="mt-1 grid" style="grid-template-columns: repeat(5, 1fr)">
                        @foreach (['Nízké', 'Střední', 'Vysoké', 'Nebezpečné', 'Extrémní'] as $label)
                            <div class="text-center text-xs text-zinc-500 dark:text-zinc-400">{{ $label }}</div>
                        @endforeach
                    </div>
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
