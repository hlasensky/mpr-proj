<div>
    {{-- Page header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight" style="color: var(--fg);">Správa manažerů</h1>
            <p class="mt-1 text-sm" style="color: var(--fg-muted);">Přehled uživatelských účtů a jejich rolí.</p>
        </div>
        <flux:button :href="route('user.editor')" wire:navigate variant="primary" icon="plus">
            Nový uživatel
        </flux:button>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            <flux:callout.text>{{ session('success') }}</flux:callout.text>
        </flux:callout>
    @endif

    @if (session('error'))
        <flux:callout variant="danger" icon="x-circle" class="mb-4">
            <flux:callout.text>{{ session('error') }}</flux:callout.text>
        </flux:callout>
    @endif

    {{-- Unverified users --}}
    @if ($unverifiedUsers->isNotEmpty())
        <div class="mb-8">
            <div class="mb-3 flex items-center gap-2">
                <span class="text-sm font-semibold" style="color: var(--fg);">Neověření uživatelé</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold"
                    style="background: color-mix(in oklab, var(--warn) 15%, var(--bg-elev)); color: var(--warn);">
                    {{ $unverifiedUsers->count() }}
                </span>
            </div>

            <div class="rounded-(--radius) border overflow-hidden" style="border-color: var(--border); box-shadow: var(--shadow-sm);">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Uživatel</flux:table.column>
                        <flux:table.column>E-mail</flux:table.column>
                        <flux:table.column>Stav</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach ($unverifiedUsers as $user)
                            <flux:table.row :key="$user->id">
                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                                             style="background: color-mix(in oklab, var(--warn) 15%, var(--bg-elev)); color: var(--warn);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: '', 1, 1)) }}
                                        </div>
                                        <span class="text-sm font-semibold" style="color: var(--fg);">{{ $user->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <span class="text-sm" style="color: var(--fg-muted);">{{ $user->email }}</span>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                                        style="background: color-mix(in oklab, var(--warn) 15%, var(--bg-elev)); color: var(--warn); border: 1px solid color-mix(in oklab, var(--warn) 25%, transparent);">
                                        <span class="size-1.5 rounded-full" style="background: var(--warn);"></span>
                                        Čeká na ověření
                                    </span>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <flux:button variant="primary" size="sm" icon="check" inset="top bottom"
                                            wire:click="verify({{ $user->id }})"
                                            wire:confirm="Ověřit uživatele {{ $user->name }} jako manažera?">
                                            Ověřit
                                        </flux:button>
                                        <flux:button variant="ghost" size="sm" icon="pencil" inset="top bottom"
                                            :href="route('user.editor', $user->id)" wire:navigate>
                                            Upravit
                                        </flux:button>
                                        <flux:button variant="danger" size="sm" icon="trash" inset="top bottom"
                                            wire:click="delete({{ $user->id }})"
                                            wire:confirm="Opravdu smazat uživatele {{ $user->name }}?">
                                            Smazat
                                        </flux:button>
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>
    @endif

    {{-- Verified users --}}
    <div class="mb-3 text-sm font-semibold" style="color: var(--fg);">Manažeři a admini</div>
    <div class="rounded-(--radius) border overflow-hidden" style="border-color: var(--border); box-shadow: var(--shadow-sm);">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Uživatel</flux:table.column>
                <flux:table.column>E-mail</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Stav</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($verifiedUsers as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <div class="flex size-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                                     style="background: color-mix(in oklab, var(--accent) 10%, var(--bg-elev)); color: var(--fg-muted);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: '', 1, 1)) }}
                                </div>
                                <span class="text-sm font-semibold" style="color: var(--fg);">{{ $user->name }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-sm" style="color: var(--fg-muted);">{{ $user->email }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" inset="top bottom" :color="$user->role->color()">
                                {{ $user->role->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                                style="background: color-mix(in oklab, var(--ok) 12%, var(--bg-elev)); color: var(--ok); border: 1px solid color-mix(in oklab, var(--ok) 25%, transparent);">
                                <span class="size-1.5 rounded-full" style="background: var(--ok);"></span>
                                Aktivní
                            </span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil" inset="top bottom"
                                    :href="route('user.editor', $user->id)" wire:navigate>
                                    Upravit
                                </flux:button>
                                <flux:button variant="danger" size="sm" icon="trash" inset="top bottom"
                                    wire:click="delete({{ $user->id }})"
                                    wire:confirm="Opravdu smazat uživatele {{ $user->name }}?"
                                    :disabled="$user->id === auth()->id()">
                                    Smazat
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-12 text-center">
                            <flux:icon.users class="mx-auto mb-3 size-10" style="color: var(--fg-subtle);" />
                            <p class="text-sm" style="color: var(--fg-muted);">Žádní ověření uživatelé.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
