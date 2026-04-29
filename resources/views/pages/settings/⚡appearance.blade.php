<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Nastavení vzhledu')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Nastavení vzhledu') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Vzhled')" :subheading="__('Upravte vizuální nastavení svého účtu')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Světlý') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Tmavý') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Systémový') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>
