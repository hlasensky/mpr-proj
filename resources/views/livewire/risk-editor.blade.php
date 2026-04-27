<div>
    @php
        use App\Enums\RiskLevelCategoryEnum;
        use App\Enums\RiskProbabilityEnum;

        $score = $impact * $likelihood;
        $band = RiskLevelCategoryEnum::matrixBand($score);
        $cat = RiskLevelCategoryEnum::fromScore($score);
    @endphp

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold tracking-tight" style="color: var(--fg);">
            {{ $risk?->exists ? 'Upravit riziko' : 'Nové riziko' }}
        </h1>
        <p class="mt-1 text-sm" style="color: var(--fg-muted);">
            {{ $risk?->exists ? "Úprava rizika {$risk->name}" : 'Přidání nového rizika k projektu.' }}
        </p>
    </div>

    <form wire:submit="save" class="max-w-2xl">
        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Main fields --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Name --}}
                <div class="rounded-(--radius) border p-5"
                    style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
                    <div class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: var(--fg-subtle);">
                        Základní údaje</div>
                    <flux:field>
                        <flux:label>Název rizika</flux:label>
                        <flux:input wire:model="name" type="text" placeholder="Např. Zpoždění dodávky hardwaru"
                            required />
                        <flux:error name="name" />
                    </flux:field>
                </div>

                {{-- Impact buttons --}}
                <div class="rounded-(--radius) border p-5"
                    style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
                    <div class="mb-1 text-sm font-semibold" style="color: var(--fg);">Dopad</div>
                    <div class="mb-3 text-xs" style="color: var(--fg-subtle);">1 = minimální dopad &nbsp;·&nbsp; 10 =
                        katastrofální</div>
                    <div class="grid grid-cols-10 gap-1">
                        @foreach (range(1, 10) as $val)
                            @php $vBand = RiskLevelCategoryEnum::matrixBand($val * $likelihood); @endphp
                            <button type="button" wire:click="$set('impact', {{ $val }})"
                                class="rounded-sm py-2 text-sm font-semibold transition-all"
                                style="{{ $impact === $val
                                    ? 'background: var(--risk-' . $vBand . '); color: #fff; box-shadow: 0 0 0 2px var(--risk-' . $vBand . ');'
                                    : 'background: var(--bg-sunken); color: var(--fg-muted);' }}">
                                {{ $val }}
                            </button>
                        @endforeach
                    </div>
                    <flux:error name="impact" />
                </div>

                {{-- Likelihood buttons --}}
                <div class="rounded-(--radius) border p-5"
                    style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
                    <div class="mb-1 text-sm font-semibold" style="color: var(--fg);">Pravděpodobnost</div>
                    <div class="mb-3 text-xs" style="color: var(--fg-subtle);">Jak pravděpodobné je, že riziko nastane
                    </div>
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach (RiskProbabilityEnum::options() as $opt)
                            @php
                                $vBand = RiskLevelCategoryEnum::matrixBand($impact * $opt['value']);
                                $isSelected = $likelihood >= $opt['min'] && $likelihood <= $opt['max'];
                            @endphp
                            <button type="button" wire:click="$set('likelihood', {{ $opt['value'] }})"
                                class="rounded-sm py-2.5 px-1 text-xs font-semibold transition-all text-center leading-snug"
                                style="{{ $isSelected
                                    ? 'background: var(--risk-' . $vBand . '); color: #fff; box-shadow: 0 0 0 2px var(--risk-' . $vBand . ');'
                                    : 'background: var(--bg-sunken); color: var(--fg-muted);' }}">
                                {{ $opt['label'] }}
                            </button>
                        @endforeach
                    </div>
                    <flux:error name="likelihood" />
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" icon="check">Uložit riziko</flux:button>
                    <flux:button :href="route('risk.overview', $projectID)" wire:navigate variant="ghost">Zrušit
                    </flux:button>
                </div>
            </div>

            {{-- Live score preview --}}
            <div class="space-y-4">
                <div class="rounded-(--radius) border p-5 text-center"
                    style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm); min-width: 200px;">
                    <div class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: var(--fg-subtle);">
                        Skóre rizika</div>

                    <div class="mx-auto mb-3 flex size-16 items-center justify-center rounded-xl text-2xl font-bold text-white"
                        style="background: var(--risk-{{ $band }}); box-shadow: 0 4px 14px color-mix(in oklab, var(--risk-{{ $band }}) 40%, transparent);">
                        {{ $score }}
                    </div>

                    <div class="text-xs mb-1" style="color: var(--fg-subtle);">z maxima 100</div>

                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap"
                        style="background: color-mix(in oklab, var(--risk-{{ $band }}) 15%, var(--bg-elev)); color: var(--risk-{{ $band }}); border: 1px solid color-mix(in oklab, var(--risk-{{ $band }}) 30%, transparent);">
                        <span class="size-1.5 rounded-full"
                            style="background: var(--risk-{{ $band }});"></span>
                        {{ $cat->label() }}
                    </span>

                    <div class="mt-4 h-2 overflow-hidden rounded-full" style="background: var(--bg-sunken);">
                        <div class="h-full rounded-full transition-all duration-300"
                            style="width: {{ $score }}%; background: var(--risk-{{ $band }});"></div>
                    </div>
                    <div class="mt-2 flex justify-between text-xs whitespace-nowrap gap-3" style="color: var(--fg-subtle);">
                        <span>Dopad: {{ $impact }}</span>
                        <span>Pravděp.: {{ RiskProbabilityEnum::bandLabel($likelihood) }}</span>
                    </div>
                </div>

                <div class="rounded-(--radius) border p-4"
                    style="background: var(--bg-elev); border-color: var(--border);">
                    <div class="text-xs font-semibold mb-3" style="color: var(--fg);">Škála skóre</div>
                    <div class="space-y-1.5">
                        @foreach ([[1, '1–6', 'Nepravděpodobné'], [2, '7–10', 'Málo pravděpodobné'], [3, '11–21', 'Možné'], [4, '22–36', 'Pravděpodobné'], [5, '37–100', 'Velmi pravděpodobné']] as [$b, $range, $lbl])
                            <div class="grid items-center gap-x-2 text-xs {{ $band === $b ? 'font-semibold' : '' }}"
                                style="grid-template-columns: 0.5rem 3.5rem 1fr; {{ $band === $b ? 'color: var(--risk-' . $b . ');' : 'color: var(--fg-subtle);' }}">
                                <span class="size-2 rounded-full"
                                    style="background: var(--risk-{{ $b }});"></span>
                                <span class="font-mono">{{ $range }}</span>
                                <span>{{ $lbl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
