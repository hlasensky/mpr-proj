<div>
    @php
        use App\Enums\RiskLevelCategoryEnum;

        $byCell = [];
        foreach ($risks as $r) {
            $byCell[$r->likelihood][$r->impact][] = $r;
        }

        $totalRisks = $risks->count();
        $avgScore = $totalRisks > 0 ? round($risks->avg(fn($r) => $r->score()), 1) : '—';
        $maxScore = $totalRisks > 0 ? $risks->max(fn($r) => $r->score()) : '—';
    @endphp

    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="max-w-2xl">
            <div class="mb-1 flex flex-wrap items-center gap-2">
                @if ($totalRisks > 0)
                    @php $topBand = RiskLevelCategoryEnum::matrixBand($maxScore); @endphp
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                        style="background: color-mix(in oklab, var(--risk-{{ $topBand }}) 15%, var(--bg-elev)); color: var(--risk-{{ $topBand }}); border: 1px solid color-mix(in oklab, var(--risk-{{ $topBand }}) 30%, transparent);">
                        <span class="size-1.5 rounded-full" style="background: var(--risk-{{ $topBand }});"></span>
                        {{ $totalRisks }} {{ $totalRisks === 1 ? 'riziko' : ($totalRisks < 5 ? 'rizika' : 'rizik') }}
                    </span>
                @endif
                @if ($project->start_date || $project->end_date)
                    <span class="text-xs font-mono" style="color: var(--fg-subtle);">
                        {{ $project->start_date?->format('d.m.Y') ?? '—' }} →
                        {{ $project->end_date?->format('d.m.Y') ?? '—' }}
                    </span>
                @endif
            </div>
            <h1 class="text-2xl font-semibold tracking-tight" style="color: var(--fg);">{{ $project->name }}</h1>
            @if ($project->description)
                <p class="mt-1 max-w-prose text-sm leading-relaxed" style="color: var(--fg-muted);">
                    {{ $project->description }}</p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button size="sm" variant="ghost" icon="pencil" :href="route('project.editor', $project->id)"
                wire:navigate>
                Upravit projekt
            </flux:button>
            <flux:button size="sm" variant="primary" icon="plus" :href="route('risk.editor', $project->id)"
                wire:navigate>
                Přidat riziko
            </flux:button>
            <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteProject"
                wire:confirm="Smazat projekt {{ $project->name }}? Všechna rizika budou také smazána.">
            </flux:button>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-(--radius) border p-4"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Celkem rizik
            </div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">{{ $totalRisks }}</div>
        </div>
        <div class="rounded-(--radius) border p-4"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Průměrné skóre
            </div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">{{ $avgScore }}</div>
        </div>
        <div class="rounded-(--radius) border p-4"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Nejvyšší skóre
            </div>
            <div class="mt-2 text-3xl font-semibold tracking-tight"
                style="color: {{ $totalRisks > 0 ? 'var(--risk-' . $topBand . ')' : 'var(--fg)' }};">
                {{ $maxScore }}
            </div>
        </div>
        <div class="rounded-(--radius) border p-4"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Max možné</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">100</div>
        </div>
    </div>

    {{-- Risk matrix card --}}
    <div class="mb-6 rounded-(--radius) border"
        style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
        <div class="flex items-center justify-between border-b px-5 py-4" style="border-color: var(--border);">
            <div>
                <div class="text-sm font-semibold tracking-tight" style="color: var(--fg);">Matice rizik</div>
                <div class="mt-0.5 text-xs" style="color: var(--fg-subtle);">Vizualizace dopadu a pravděpodobnosti</div>
            </div>
            <div class="flex overflow-hidden rounded-sm border text-xs font-medium"
                style="border-color: var(--border);">
                <button wire:click="$set('matrixView', 'grid')" class="px-3 py-1.5 transition-colors"
                    style="{{ $matrixView === 'grid' ? 'background: var(--bg-hover); color: var(--fg);' : 'background: var(--bg-elev); color: var(--fg-muted);' }}">
                    Mřížka
                </button>
                <button wire:click="$set('matrixView', 'bubble')" class="border-l px-3 py-1.5 transition-colors"
                    style="border-color: var(--border); {{ $matrixView === 'bubble' ? 'background: var(--bg-hover); color: var(--fg);' : 'background: var(--bg-elev); color: var(--fg-muted);' }}">
                    Bubliny
                </button>
            </div>
        </div>

        <div class="p-5">
            @if ($matrixView === 'grid')
                {{-- 10×10 grid matrix --}}
                @php
                    $impCategories = [
                        ['label' => 'Nízký', 'col' => '2/4'],
                        ['label' => 'Střední', 'col' => '4/6'],
                        ['label' => 'Normální', 'col' => '6/8'],
                        ['label' => 'Vysoký', 'col' => '8/10'],
                        ['label' => 'Velmi vysoký', 'col' => '10/12'],
                    ];
                    $likCategories = [
                        ['label' => 'Velmi velká', 'row' => '2/4'],
                        ['label' => 'Velká', 'row' => '4/6'],
                        ['label' => 'Střední', 'row' => '6/8'],
                        ['label' => 'Nízká', 'row' => '8/10'],
                        ['label' => 'Velmi nízká', 'row' => '10/12'],
                    ];
                @endphp

                <div class="flex gap-3">
                    <div class="flex items-center justify-center">
                        <span class="text-xs font-medium [writing-mode:vertical-rl] rotate-180"
                            style="color: var(--fg-subtle);">Pravděpodobnost →</span>
                    </div>
                    <div class="flex-1 overflow-x-auto">
                        <div class="grid min-w-160" style="grid-template-columns: 5.5rem repeat(10, 1fr)">

                            <div style="grid-row: 1; grid-column: 1"></div>

                            @foreach ($impCategories as $cat)
                                <div style="grid-row: 1; grid-column: {{ $cat['col'] }}"
                                    class="pb-1 text-center text-xs font-semibold" style="color: var(--fg-muted);">
                                    {{ $cat['label'] }}
                                </div>
                            @endforeach

                            @foreach ($likCategories as $cat)
                                <div style="grid-row: {{ $cat['row'] }}; grid-column: 1"
                                    class="flex items-center justify-end pr-2 text-right text-xs font-semibold leading-tight"
                                    style="color: var(--fg-muted);">
                                    {{ $cat['label'] }}
                                </div>
                            @endforeach

                            @foreach ([10, 9, 8, 7, 6, 5, 4, 3, 2, 1] as $rowIdx => $lik)
                                @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] as $colIdx => $imp)
                                    @php
                                        $gridRow = $rowIdx + 2;
                                        $gridCol = $colIdx + 2;
                                        $score = $lik * $imp;
                                        $band = RiskLevelCategoryEnum::matrixBand($score);
                                        $cellRisks = $byCell[$lik][$imp] ?? [];
                                        $shown = array_slice($cellRisks, 0, 2);
                                        $extra = count($cellRisks) - count($shown);
                                    @endphp
                                    <div style="grid-row: {{ $gridRow }}; grid-column: {{ $gridCol }}; background: color-mix(in oklab, var(--risk-{{ $band }}) 55%, var(--bg));"
                                        class="relative min-h-12 overflow-hidden border border-white/10 p-1">
                                        @foreach ($shown as $risk)
                                            <a href="{{ route('risk.editor', [$project->id, $risk->id]) }}"
                                                wire:navigate
                                                class="mb-0.5 flex items-center rounded px-1.5 py-0.5 text-xs font-medium leading-tight transition-opacity hover:opacity-80"
                                                style="background: color-mix(in oklab, var(--risk-{{ $band }}) 80%, transparent); color: #fff;"
                                                title="{{ $risk->name }}">
                                                <span class="truncate">{{ $risk->name }}</span>
                                            </a>
                                        @endforeach
                                        @if ($extra > 0)
                                            <span class="block text-center text-xs font-semibold"
                                                style="color: rgba(255,255,255,0.7);">+{{ $extra }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="mt-1 pl-22 text-center text-xs font-medium" style="color: var(--fg-subtle);">→ Dopad
                        </div>


                    </div>
                </div>
            @else
                {{-- Bubble view --}}
                <div class="flex gap-3">
                    <div class="flex items-center justify-center">
                        <span class="text-xs font-medium [writing-mode:vertical-rl] rotate-180"
                            style="color: var(--fg-subtle);">Pravděpodobnost →</span>
                    </div>
                    <div class="flex-1">
                        <div class="relative"
                            style="height: 340px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-sunken);">
                            {{-- Grid lines --}}
                            @for ($i = 1; $i <= 9; $i++)
                                <div class="absolute top-0 h-full border-r"
                                    style="left: {{ $i * 10 }}%; border-color: var(--border);"></div>
                                <div class="absolute left-0 w-full border-b"
                                    style="top: {{ $i * 10 }}%; border-color: var(--border);"></div>
                            @endfor

                            {{-- Bubbles --}}
                            @foreach ($risks as $risk)
                                @php
                                    $s = $risk->score();
                                    $band = RiskLevelCategoryEnum::matrixBand($s);
                                    $size = 22 + $s * 0.4;
                                    $left = (($risk->impact - 0.5) / 10) * 100;
                                    $bot = (($risk->likelihood - 0.5) / 10) * 100;
                                @endphp
                                <div class="absolute flex items-center justify-center rounded-full text-xs font-semibold text-white transition-transform hover:scale-110"
                                    title="{{ $risk->name }} ({{ $s }})"
                                    style="
                                        width: {{ $size }}px;
                                        height: {{ $size }}px;
                                        left: calc({{ $left }}% - {{ $size / 2 }}px);
                                        bottom: calc({{ $bot }}% - {{ $size / 2 }}px);
                                        background: var(--risk-{{ $band }});
                                        opacity: 0.9;
                                        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                                     ">
                                    {{ $s }}
                                </div>
                            @endforeach

                            {{-- X-axis labels --}}
                            @foreach (['Nízký', 'Střední', 'Normální', 'Vysoký', 'Velmi vysoký'] as $i => $lbl)
                                <div class="absolute bottom-0 text-xs translate-y-6 -translate-x-1/2"
                                    style="left: {{ ($i * 2 + 1) * 10 }}%; color: var(--fg-subtle);">
                                    {{ $lbl }}</div>
                            @endforeach

                            {{-- Y-axis labels --}}
                            @foreach (['Nízké', 'Střední', 'Vysoké', 'Nebezpečné', 'Extrémní'] as $i => $lbl)
                                <div class="absolute left-0 text-xs -translate-x-full -translate-y-1/2 pr-2 text-right whitespace-nowrap"
                                    style="bottom: {{ ($i * 2 + 1) * 10 }}%; color: var(--fg-subtle);">
                                    {{ $lbl }}</div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center text-xs font-medium" style="color: var(--fg-subtle);">→ Dopad
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Risk table --}}
    <div class="mb-3 flex items-center justify-between">
        <div class="text-sm font-semibold tracking-tight" style="color: var(--fg);">Seznam rizik</div>
    </div>

    <div class="rounded-(--radius) border overflow-hidden"
        style="border-color: var(--border); box-shadow: var(--shadow-sm);">
        <flux:table>
            <flux:table.columns>
                <flux:table.column></flux:table.column>
                <flux:table.column>Název</flux:table.column>
                <flux:table.column>Dopad</flux:table.column>
                <flux:table.column>Pravděpodobnost</flux:table.column>
                <flux:table.column>Skóre</flux:table.column>
                <flux:table.column>Kategorie</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($risks as $risk)
                    @php
                        $s = $risk->score();
                        $b = RiskLevelCategoryEnum::matrixBand($s);
                    @endphp
                    <flux:table.row :key="$risk->id">
                        <flux:table.cell>
                            <span class="font-mono text-xs"
                                style="color: var(--fg-subtle);">#{{ str_pad($risk->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $risk->name }}</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $risk->impact }}</flux:table.cell>
                        <flux:table.cell class="font-mono">{{ $risk->likelihood }}</flux:table.cell>
                        <flux:table.cell>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-xs font-semibold"
                                style="background: color-mix(in oklab, var(--risk-{{ $b }}) 15%, var(--bg-elev)); color: var(--risk-{{ $b }}); border: 1px solid color-mix(in oklab, var(--risk-{{ $b }}) 30%, transparent);">
                                {{ $s }}
                            </span>
                        </flux:table.cell>
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
                        <flux:table.cell colspan="7" class="py-12 text-center">
                            <flux:icon.shield-exclamation class="mx-auto mb-3 size-10"
                                style="color: var(--fg-subtle);" />
                            <p class="text-sm font-medium" style="color: var(--fg-muted);">Žádná rizika</p>
                            <p class="mt-1 text-xs" style="color: var(--fg-subtle);">Začněte přidáním prvního rizika.
                            </p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
