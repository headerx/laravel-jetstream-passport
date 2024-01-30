<x-guest-layout>

    <div class="fixed top-0 min-w-full px-6 py-3 mb-0 text-center bg-white border-b-1 border-grey-light text-grey-lightest dark:bg-gray-800">
        Authorization Request
    </div>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>



        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>
        </div>

        <!-- Scope List -->
        @if (count($scopes) > 0)
            <div class="scopes">
                <p><strong>This application will be able to:</strong></p>

                <ul>
                    @foreach ($scopes as $scope)
                        <li>{{ $scope->description }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-validation-errors class="mb-4" />
        <div class="flex items-center justify-end mt-4">

            <form method="POST" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">

                <div class="flex justify-end mt-4">
                    <x-button class="ml-4 bg-red-500 dark:bg-red-800">
                        {{ __('Cancel') }}
                    </x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('passport.authorizations.approve') }}">
                @csrf

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <div class="flex justify-end mt-4">
                    <x-button class="ml-4">
                        {{ __('Authorize') }}
                    </x-button>
                </div>
            </form>

        </div>

    </x-authentication-card>
</x-guest-layout>
