<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        body {
            background: var(--bg);
            color: var(--fg);
        }

        /* Sidebar shell */
        .app-sidebar {
            background: var(--bg-sunken);
            border-right: 1px solid var(--border);
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 16px 14px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand-mark {
            width: 28px;
            height: 28px;
            background: var(--fg);
            color: var(--bg);
            border-radius: 6px;
            display: grid;
            place-items: center;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.02em;
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--fg);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            color: var(--fg-subtle);
            line-height: 1.2;
        }

        /* Nav group label */
        .sidebar-nav-label {
            padding: 14px 16px 4px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--fg-subtle);
        }

        /* Nav item overrides */
        [data-flux-sidebar-item] {
            border-radius: var(--radius-sm) !important;
            margin: 1px 8px !important;
            padding: 7px 10px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: var(--fg-muted) !important;
            transition: background 120ms, color 120ms !important;
        }

        [data-flux-sidebar-item]:hover {
            background: var(--bg-hover) !important;
            color: var(--fg) !important;
        }

        [data-flux-sidebar-item][data-current] {
            background: var(--bg-hover) !important;
            color: var(--fg) !important;
        }

        /* Badge count in nav */
        .nav-count {
            margin-left: auto;
            font-size: 11px;
            font-family: var(--font-mono);
            color: var(--fg-subtle);
        }

        /* Dark toggle button */
        .dark-toggle {
            margin: 2px 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            color: var(--fg-muted);
            cursor: pointer;
            background: transparent;
            border: none;
            width: calc(100% - 16px);
            text-align: left;
            transition: background 120ms, color 120ms;
        }

        .dark-toggle:hover {
            background: var(--bg-hover);
            color: var(--fg);
        }

        /* User card */
        [data-flux-sidebar]>[data-flux-profile] {
            margin: 8px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--bg-elev);
            padding: 10px 12px;
        }

        /* Main content area */
        .app-main {
            background: var(--bg);
        }

        /* Flux sidebar background override */
        [data-flux-sidebar] {
            background: var(--bg-sunken) !important;
            border-color: var(--border) !important;
        }

        /* Page heading */
        .page-header-bar {
            border-bottom: 1px solid var(--border);
            background: var(--bg-elev);
            padding: 0 24px;
            height: 52px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Content wrapper */
        .page-content {
            padding: 24px;
        }
    </style>
</head>

<body class="min-h-screen">
    <flux:sidebar sticky collapsible="mobile" class="app-sidebar">
        <flux:sidebar.header class="p-0!">
            <div class="sidebar-brand">
                <div class="sidebar-brand-mark">R</div>
                <div>
                    <div class="sidebar-brand-name">Risk Manager</div>
                    <div class="sidebar-brand-sub">MPR projekt</div>
                </div>
            </div>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <div class="sidebar-nav-label">Platforma</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                Dashboard
            </flux:sidebar.item>

            @role(\App\Enums\RoleEnum::Admin)
                <flux:sidebar.item icon="users" :href="route('user.overview')" :current="request()->routeIs('user.*')"
                    wire:navigate>
                    Správa manažerů
                </flux:sidebar.item>
            @endrole
        </flux:sidebar.nav>
        <flux:spacer />
        <div class="sidebar-nav-label">Účet</div>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" :href="route('profile.edit')"
                :current="request()->routeIs('profile.*')" wire:navigate>
                Nastavení
            </flux:sidebar.item>
        </flux:sidebar.nav>




        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile header -->
    <flux:header class="lg:hidden" style="background: var(--bg-sunken); border-bottom: 1px solid var(--border);">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Nastavení') }}
                    </flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('Odhlásit se') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
