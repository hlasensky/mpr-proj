<div>
    @php
        use App\Enums\RiskLevelCategoryEnum;
        use App\Enums\RiskLevelEnum;
        use App\Enums\RiskProbabilityEnum;

        $likBands = RiskProbabilityEnum::bands();
        $impBands = RiskLevelEnum::bands();

        $byCell = [];
        foreach ($risks as $r) {
            $byCell[$r->likelihood][$r->impact][] = $r;
        }

        $totalRisks = $risks->count();
        $avgScore = $totalRisks > 0 ? round($risks->avg(fn($r) => $r->score()), 1) : '—';
        $maxScore = $totalRisks > 0 ? $risks->max(fn($r) => $r->score()) : '—';
        $topBand = $totalRisks > 0 ? RiskLevelCategoryEnum::matrixBand($maxScore) : 1;
    @endphp

    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="max-w-2xl">
            <div class="mb-1 flex flex-wrap items-center gap-2">
                @if ($totalRisks > 0)
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

    {{-- Matrix + List: 50/50 grid on xl, stacked below --}}
    <div class="flex flex-col min-[1600px]:grid min-[1600px]:grid-cols-2 gap-6" x-data="{ activeRisk: null }">

        {{-- Risk matrix card --}}
        <div class="rounded-(--radius) border"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="flex items-center justify-between border-b px-5 py-4" style="border-color: var(--border);">
                <div>
                    <div class="text-sm font-semibold tracking-tight" style="color: var(--fg);">Matice rizik</div>
                    <div class="mt-0.5 text-xs" style="color: var(--fg-subtle);">Vizualizace dopadu a pravděpodobnosti
                    </div>
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

            <div class="p-4">
                @if ($matrixView === 'grid')
                    <div class="flex gap-2">
                        {{-- Y-axis label --}}
                        <div class="flex items-center">
                            <span class="text-xs font-medium [writing-mode:vertical-rl] rotate-180"
                                style="color: var(--fg-subtle);">Pravděpodobnost →</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- Impact column headers (5 labels, each spanning 2 of 10 columns) --}}
                            <div class="grid grid-cols-10 mb-1 ml-[3.75rem]">
                                @foreach ($impBands as $ib)
                                    <div class="col-span-2 text-center text-xs font-medium truncate px-0.5"
                                        style="color: var(--fg-muted);">{{ $ib['label'] }}</div>
                                @endforeach
                            </div>

                            {{-- Row labels + 10×10 data grid --}}
                            <div class="flex items-start">
                                {{-- 5 likelihood labels, each flex-1 = 2 grid rows --}}
                                <div class="flex flex-col shrink-0 self-stretch" style="width: 3.75rem;">
                                    @foreach ($likBands as $lb)
                                        <div class="flex flex-1 items-center justify-end pr-2 text-right text-xs font-medium leading-tight"
                                            style="color: var(--fg-muted);">{{ $lb['label'] }}</div>
                                    @endforeach
                                </div>

                                {{-- 10×10 square grid --}}
                                <div class="flex-1 aspect-square grid grid-cols-10 grid-rows-10">
                                    @foreach ([10, 9, 8, 7, 6, 5, 4, 3, 2, 1] as $lik)
                                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] as $imp)
                                            @php
                                                $score = $lik * $imp;
                                                $band = RiskLevelCategoryEnum::matrixBand($score);
                                                $cellRisks = $byCell[$lik][$imp] ?? [];
                                                $shown = array_slice($cellRisks, 0, 2);
                                                $extra = max(0, count($cellRisks) - 2);
                                            @endphp
                                            <div class="overflow-hidden border border-white/10 flex flex-col items-center justify-center gap-px p-px"
                                                style="background: color-mix(in oklab, var(--risk-{{ $band }}) 55%, var(--bg));">
                                                @foreach ($shown as $risk)
                                                    <a href="#risk-{{ $risk->id }}"
                                                        class="font-mono font-normal leading-none hover:opacity-70 truncate w-full text-center rounded-sm"
                                                        style="font-size: 11px; padding: 1px 2px; color: #fff; background: color-mix(in oklab, var(--risk-{{ $band }}) 85%, var(--bg)); outline: 1px solid color-mix(in oklab, var(--risk-{{ $band }}) 60%, black); text-shadow: 0 1px 2px rgba(0,0,0,0.3);"
                                                        title="{{ $risk->name }}"
                                                        @mouseenter="activeRisk = {{ $risk->id }}"
                                                        @mouseleave="activeRisk = null">
                                                        #{{ str_pad($risk->id, 3, '0', STR_PAD_LEFT) }}
                                                    </a>
                                                @endforeach
                                                @if ($extra > 0)
                                                    <span class="font-bold leading-none text-center w-full"
                                                        style="font-size: 9px; color: rgba(255,255,255,0.85);">+{{ $extra }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>

                            {{-- X-axis label --}}
                            <div class="mt-1 ml-[3.75rem] text-center text-xs font-medium"
                                style="color: var(--fg-subtle);">→ Dopad</div>
                        </div>
                    </div>
                @else
                    {{-- Bubble view --}}
                    <div class="flex gap-2">
                        {{-- Y-axis label --}}
                        <div class="flex items-center">
                            <span class="text-xs font-medium [writing-mode:vertical-rl] rotate-180"
                                style="color: var(--fg-subtle);">Pravděpodobnost →</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- Likelihood row labels + chart --}}
                            <div class="flex items-stretch gap-1">
                                {{-- Y-axis category labels (5 equal sections) --}}
                                <div class="flex flex-col shrink-0" style="width: 3.75rem;">
                                    @foreach ($likBands as $lb)
                                        <div class="flex flex-1 items-center justify-end pr-2 text-right text-[10px] font-medium leading-tight"
                                            style="color: var(--fg-muted);">{{ $lb['label'] }}</div>
                                    @endforeach
                                </div>

                                {{-- Chart area --}}
                                <div class="flex-1 min-w-0">
                                    <div class="relative w-full"
                                        style="height: 280px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-sunken);">
                                        @for ($i = 1; $i <= 9; $i++)
                                            <div class="absolute top-0 h-full border-r"
                                                style="left: {{ $i * 10 }}%; border-color: var(--border);"></div>
                                            <div class="absolute left-0 w-full border-b"
                                                style="top: {{ $i * 10 }}%; border-color: var(--border);"></div>
                                        @endfor

                                        @foreach ($risks as $risk)
                                            @php
                                                $s = $risk->score();
                                                $band = RiskLevelCategoryEnum::matrixBand($s);
                                                $size = 22 + $s * 0.4;
                                                $left = (($risk->impact - 0.5) / 10) * 100;
                                                $bot = (($risk->likelihood - 0.5) / 10) * 100;
                                            @endphp
                                            <a href="#risk-{{ $risk->id }}"
                                                class="absolute flex items-center justify-center rounded-full text-xs font-semibold text-white transition-all hover:scale-110 hover:z-10"
                                                title="{{ $risk->name }} ({{ $s }})"
                                                style="width: {{ $size }}px; height: {{ $size }}px; left: calc({{ $left }}% - {{ $size / 2 }}px); bottom: calc({{ $bot }}% - {{ $size / 2 }}px); background: var(--risk-{{ $band }}); box-shadow: 0 0 0 2px var(--bg-elev), 0 2px 8px rgba(0,0,0,0.25);"
                                                @mouseenter="activeRisk = {{ $risk->id }}"
                                                @mouseleave="activeRisk = null">
                                                {{ $s }}
                                            </a>
                                        @endforeach
                                    </div>

                                    {{-- X-axis labels --}}
                                    <div class="grid mt-1" style="grid-template-columns: repeat(5, 1fr);">
                                        @foreach ($impBands as $ib)
                                            <div class="text-center text-[10px] font-medium leading-tight"
                                                style="color: var(--fg-subtle);">{{ $ib['label'] }}</div>
                                        @endforeach
                                    </div>
                                    <div class="mt-0.5 text-center text-xs font-medium"
                                        style="color: var(--fg-subtle);">→ Dopad</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Risk list (compact) --}}
        <div class="rounded-(--radius) border overflow-hidden"
            style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="flex items-center border-b px-5 py-4" style="border-color: var(--border);">
                <div>
                    <div class="text-sm font-semibold tracking-tight" style="color: var(--fg);">Seznam rizik</div>
                    <div class="mt-0.5 text-xs" style="color: var(--fg-subtle);">Přehled všech rizik projektu</div>
                </div>
            </div>

            <div class="overflow-auto">
                <table class="w-full text-sm ">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border); background: var(--bg-elev);">
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">#</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">Název</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">Dopad</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">Pravd.</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">Skóre</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider"
                                style="color: var(--fg-subtle);">Úroveň</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="border-b" style="background: var(--bg-elev); border-color: var(--border);">
                        @forelse ($risks as $risk)
                            @php
                                $s = $risk->score();
                                $b = RiskLevelCategoryEnum::matrixBand($s);
                            @endphp
                            <tr id="risk-{{ $risk->id }}"
                                class="border-t transition-colors"
                                :style="activeRisk === {{ $risk->id }}
                                    ? 'border-color: var(--border); background: color-mix(in oklab, var(--risk-{{ $b }}) 12%, var(--bg-elev)); box-shadow: inset 3px 0 0 var(--risk-{{ $b }});'
                                    : 'border-color: var(--border);'"
                                @mouseenter="activeRisk = {{ $risk->id }}"
                                @mouseleave="activeRisk = null">
                                <td class="px-3 py-2">
                                    <span class="font-mono text-xs"
                                        style="color: var(--fg-subtle);">#{{ str_pad($risk->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="text-sm font-medium"
                                        style="color: var(--fg);">{{ $risk->name }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="text-xs"
                                        style="color: var(--fg-muted);">{{ RiskLevelEnum::bandLabel($risk->impact) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="text-xs"
                                        style="color: var(--fg-muted);">{{ RiskProbabilityEnum::bandLabel($risk->likelihood) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                                        style="background: color-mix(in oklab, var(--risk-{{ $b }}) 15%, var(--bg-elev)); color: var(--risk-{{ $b }}); border: 1px solid color-mix(in oklab, var(--risk-{{ $b }}) 35%, transparent);">
                                        {{ $s }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <flux:badge size="sm" inset="top bottom"
                                        :color="$risk->riskCategory()->fluxColor()">
                                        {{ $risk->riskCategory()->label() }}
                                    </flux:badge>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-1">
                                        <flux:button size="sm" variant="ghost" icon="pencil" inset="top bottom"
                                            :href="route('risk.editor', [$project->id, $risk->id])"
                                            wire:navigate />
                                        <flux:button size="sm" variant="danger" icon="trash" inset="top bottom"
                                            wire:click="deleteRisk({{ $risk->id }})"
                                            wire:confirm="Smazat riziko {{ $risk->name }}?" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center">
                                    <flux:icon.shield-exclamation class="mx-auto mb-3 size-8"
                                        style="color: var(--fg-subtle);" />
                                    <p class="text-sm font-medium" style="color: var(--fg-muted);">Žádná rizika</p>
                                    <p class="mt-0.5 text-xs" style="color: var(--fg-subtle);">Začněte přidáním prvního
                                        rizika.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
