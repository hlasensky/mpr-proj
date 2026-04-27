<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen antialiased" style="background: var(--bg); color: var(--fg);">
    <div class="grid h-dvh lg:grid-cols-2">

        {{-- Left panel --}}
        <div class="relative hidden lg:flex flex-col p-12" style="background: var(--bg-sunken); border-right: 1px solid var(--border);">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" wire:navigate>
                <div class="flex size-8 items-center justify-center rounded-md text-sm font-bold"
                     style="background: var(--fg); color: var(--bg);">R</div>
                <span class="text-sm font-semibold" style="color: var(--fg);">Risk Manager</span>
            </a>

            {{-- Hero text --}}
            <div class="mt-auto max-w-sm">
                <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: var(--fg-subtle);">MPR projekt</p>
                <h2 class="text-3xl font-semibold tracking-tight leading-tight mb-4" style="color: var(--fg); font-family: 'Instrument Serif', serif;">
                    Řízení rizik pro moderní projekty
                </h2>
                <p class="text-sm leading-relaxed" style="color: var(--fg-muted);">
                    Sledujte, hodnoťte a mitigujte projektová rizika přehledně na jednom místě.
                </p>

                <div class="mt-8 space-y-3">
                    @foreach ([
                        ['5×5 matice rizik', 'Vizualizace dopadu a pravděpodobnosti'],
                        ['Správa projektů', 'Přehled všech rizik v jednom místě'],
                        ['Světlý i tmavý režim', 'Přizpůsobte si prostředí podle sebe'],
                    ] as [$title, $desc])
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 size-5 rounded-full flex items-center justify-center shrink-0"
                                 style="background: color-mix(in oklab, var(--risk-2) 20%, var(--bg-sunken)); color: var(--risk-2);">
                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold" style="color: var(--fg);">{{ $title }}</div>
                                <div class="text-xs" style="color: var(--fg-muted);">{{ $desc }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right panel: form slot --}}
        <div class="flex items-center justify-center px-8 py-12">
            <div class="w-full max-w-sm">
                {{-- Mobile brand --}}
                <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2.5 lg:hidden" wire:navigate>
                    <div class="flex size-8 items-center justify-center rounded-md text-sm font-bold"
                         style="background: var(--fg); color: var(--bg);">R</div>
                    <span class="text-sm font-semibold" style="color: var(--fg);">Risk Manager</span>
                </a>

                {{ $slot }}
            </div>
        </div>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
