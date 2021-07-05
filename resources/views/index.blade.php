<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Api Tokens') }}
        </h2>
    </x-slot>

    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('jetstream-passport.oauth-client-manager')
        </div>
    </div>

    <x-jet-section-border />

    <div>
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('jetstream-passport.api-token-manager')
        </div>
    </div>
</x-app-layout>
