<x-guest-layout>

    <div class="py-3 px-6 mb-0 bg-blue-dark border-b-1 border-grey-light text-grey-lightest text-center">
        Authorization Request
    </div>

    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>



        <div class="mb-4 text-sm text-gray-600">
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

        <x-jet-validation-errors class="mb-4" />
        <div class="flex items-center justify-end mt-4">

            <form method="POST" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">

                <div class="flex justify-end mt-4">
                    <x-jet-button class="ml-4 bg-red-500">
                        {{ __('Cancel') }}
                    </x-jet-button>
                </div>
            </form>

            <form method="POST" action="{{ route('passport.authorizations.approve') }}">
                @csrf

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <div class="flex justify-end mt-4">
                    <x-jet-button class="ml-4">
                        {{ __('Authorize') }}
                    </x-jet-button>
                </div>
            </form>

        </div>

    </x-jet-authentication-card>
</x-guest-layout>
