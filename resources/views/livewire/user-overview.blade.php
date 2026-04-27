<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">Správa manažerů</flux:heading>
            <flux:text variant="subtle" class="mt-1">Přehled uživatelských účtů a jejich rolí.</flux:text>
        </div>
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
            <flux:heading size="lg" level="2" class="mb-3">Neověření uživatelé</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Jméno</flux:table.column>
                    <flux:table.column>E-mail</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($unverifiedUsers as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                            <flux:table.cell>{{ $user->email }}</flux:table.cell>
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
                                        wire:confirm="Opravdu chcete smazat uživatele {{ $user->name }}?">
                                        Smazat
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    @endif

    {{-- Verified users --}}
    <flux:heading size="lg" level="2" class="mb-3">Manažeři a admini</flux:heading>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Jméno</flux:table.column>
            <flux:table.column>E-mail</flux:table.column>
            <flux:table.column>Role</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($verifiedUsers as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" inset="top bottom"
                            :color="$user->role->color()">
                            {{ $user->role->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:button variant="ghost" size="sm" icon="pencil" inset="top bottom"
                                :href="route('user.editor', $user->id)" wire:navigate>
                                Upravit
                            </flux:button>
                            <flux:button variant="danger" size="sm" icon="trash" inset="top bottom"
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="Opravdu chcete smazat uživatele {{ $user->name }}?"
                                :disabled="$user->id === auth()->id()"
                                class="disabled:cursor-not-allowed">
                                Smazat
                            </flux:button>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center">
                        <flux:text variant="subtle">Žádní ověření uživatelé nenalezeni.</flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
