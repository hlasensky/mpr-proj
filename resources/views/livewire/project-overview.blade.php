<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">Projekty</flux:heading>
            <flux:text variant="subtle" class="mt-1">Přehled všech projektů a jejich rizik.</flux:text>
        </div>
        <flux:button :href="route('project.editor')" wire:navigate variant="primary" icon="plus">
            Nový projekt
        </flux:button>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-6">
            <flux:callout.text>{{ session('success') }}</flux:callout.text>
        </flux:callout>
    @endif

    @if ($projects->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 py-16 dark:border-zinc-700">
            <flux:icon.folder-open class="size-12 text-zinc-400 dark:text-zinc-500" />
            <flux:heading class="mt-4">Žádné projekty</flux:heading>
            <flux:text variant="subtle" class="mt-1">Vytvořte svůj první projekt.</flux:text>
            <flux:button :href="route('project.editor')" wire:navigate variant="primary" icon="plus" class="mt-4">
                Nový projekt
            </flux:button>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($projects as $project)
                <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <flux:heading size="lg" class="truncate">{{ $project->name }}</flux:heading>
                            @if ($project->description)
                                <flux:text variant="subtle" class="mt-1 line-clamp-2 text-sm">{{ $project->description }}</flux:text>
                            @endif
                        </div>
                        <flux:badge size="sm" :color="$project->risks_count > 0 ? 'red' : 'green'" class="shrink-0">
                            {{ $project->risks_count }} {{ $project->risks_count === 1 ? 'riziko' : ($project->risks_count < 5 ? 'rizika' : 'rizik') }}
                        </flux:badge>
                    </div>

                    @if ($project->start_date || $project->end_date)
                        <div class="flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400">
                            <flux:icon.calendar class="size-4 shrink-0" />
                            <span>
                                {{ $project->start_date?->format('d.m.Y') ?? '—' }}
                                →
                                {{ $project->end_date?->format('d.m.Y') ?? '—' }}
                            </span>
                        </div>
                    @endif

                    @role(\App\Enums\RoleEnum::Admin)
                        <flux:text variant="subtle" class="text-xs">
                            Vlastník: {{ $project->user?->name ?? '—' }}
                        </flux:text>
                    @endrole

                    <div class="mt-auto border-t border-zinc-100 pt-4 dark:border-zinc-800">
                        <flux:button size="sm" variant="ghost" icon="arrow-right"
                            :href="route('risk.overview', $project->id)" wire:navigate class="w-full">
                            Otevřít projekt
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
