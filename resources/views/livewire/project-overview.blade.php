<div>
    {{-- Page header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight" style="color: var(--fg);">Projekty</h1>
            <p class="mt-1 text-sm" style="color: var(--fg-muted);">Přehled všech projektů a jejich rizik.</p>
        </div>
        <flux:button :href="route('project.editor')" wire:navigate variant="primary" icon="plus">
            Nový projekt
        </flux:button>
    </div>

    {{-- Stat cards --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Aktivní projekty</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">{{ $projects->count() }}</div>
        </div>
        <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Otevřená rizika</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">{{ $projects->sum('risks_count') }}</div>
        </div>
        <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Průměrně rizik</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">
                {{ $projects->count() > 0 ? round($projects->avg('risks_count'), 1) : '—' }}
            </div>
        </div>
        <div class="rounded-(--radius) border p-4" style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">
            <div class="text-xs font-medium uppercase tracking-widest" style="color: var(--fg-subtle);">Nejvíce rizik</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight" style="color: var(--fg);">
                {{ $projects->count() > 0 ? $projects->max('risks_count') : '—' }}
            </div>
        </div>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-6">
            <flux:callout.text>{{ session('success') }}</flux:callout.text>
        </flux:callout>
    @endif

    @if ($projects->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-(--radius) border border-dashed py-16" style="border-color: var(--border);">
            <flux:icon.folder-open class="size-12" style="color: var(--fg-subtle);" />
            <h3 class="mt-4 text-base font-semibold" style="color: var(--fg);">Žádné projekty</h3>
            <p class="mt-1 text-sm" style="color: var(--fg-muted);">Vytvořte svůj první projekt.</p>
            <flux:button :href="route('project.editor')" wire:navigate variant="primary" icon="plus" class="mt-4">
                Nový projekt
            </flux:button>
        </div>
    @else
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($projects as $project)
                @php
                    $rc = $project->risks_count;
                    $band = $rc === 0 ? 1 : ($rc <= 3 ? 2 : ($rc <= 7 ? 3 : ($rc <= 11 ? 4 : 5)));
                @endphp
                <div class="group relative flex flex-col gap-3 rounded-(--radius) border p-5 transition-shadow"
                    style="background: var(--bg-elev); border-color: var(--border); box-shadow: var(--shadow-sm);">

                    {{-- Risk count pill (top-right) --}}
                    <span class="absolute right-4 top-4 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                        style="background: color-mix(in oklab, var(--risk-{{ $band }}) 15%, var(--bg-elev)); color: var(--risk-{{ $band }}); border: 1px solid color-mix(in oklab, var(--risk-{{ $band }}) 30%, transparent);">
                        <span class="size-1.5 rounded-full" style="background: var(--risk-{{ $band }});"></span>
                        {{ $rc }} {{ $rc === 1 ? 'riziko' : ($rc < 5 ? 'rizika' : 'rizik') }}
                    </span>

                    {{-- Title + desc --}}
                    <div class="pr-20">
                        <h3 class="truncate text-sm font-semibold" style="color: var(--fg);">{{ $project->name }}</h3>
                        @if ($project->description)
                            <p class="mt-1 line-clamp-2 text-xs leading-relaxed" style="color: var(--fg-muted);">{{ $project->description }}</p>
                        @endif
                    </div>

                    {{-- Dates --}}
                    @if ($project->start_date || $project->end_date)
                        <div class="flex items-center gap-1.5 text-xs font-mono" style="color: var(--fg-subtle);">
                            <flux:icon.calendar class="size-3.5 shrink-0" />
                            {{ $project->start_date?->format('d.m.Y') ?? '—' }}
                            →
                            {{ $project->end_date?->format('d.m.Y') ?? '—' }}
                        </div>
                    @endif

                    {{-- Owner (admin only) --}}
                    @role(\App\Enums\RoleEnum::Admin)
                        <p class="text-xs" style="color: var(--fg-subtle);">
                            Vlastník: <span style="color: var(--fg-muted);">{{ $project->user?->name ?? '—' }}</span>
                        </p>
                    @endrole

                    {{-- Footer --}}
                    <div class="mt-auto flex items-center justify-between border-t pt-3" style="border-color: var(--border);">
                        <a href="{{ route('risk.overview', $project->id) }}" wire:navigate
                            class="inline-flex items-center gap-1 text-xs font-medium transition-colors"
                            style="color: var(--fg-muted);">
                            Otevřít
                            <flux:icon.arrow-right class="size-3.5" />
                        </a>
                        @role(\App\Enums\RoleEnum::Admin)
                            <button wire:click="delete({{ $project->id }})"
                                wire:confirm="Opravdu smazat projekt {{ $project->name }}?"
                                class="text-xs transition-colors hover:text-red-500" style="color: var(--fg-subtle);">
                                <flux:icon.trash class="size-3.5" />
                            </button>
                        @endrole
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
