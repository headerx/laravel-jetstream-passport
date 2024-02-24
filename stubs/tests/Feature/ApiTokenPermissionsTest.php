<?php

use App\Models\User;
use HeaderX\JetstreamPassport\Http\Livewire\ApiTokenManager;
use Illuminate\Support\Facades\Artisan;
use Laravel\Jetstream\Features;
use Livewire\Livewire;

test('api token permissions can be updated', function () {
    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    Artisan::call('passport:client', ['--personal' => true, '--name' => 'Laravel Personal Access Client']);

    $token = $user->createToken('Test Token', ['create', 'read'])->token;

    Livewire::test(ApiTokenManager::class)
        ->set(['managingPermissionsForId' => $token->id])
        ->set(['updateApiTokenForm' => [
            'scopes' => [
                'delete',
                'missing-permission',
            ],
        ]])
        ->call('updateApiToken');

    expect($user->fresh()->tokens->first())
        ->can('delete')->toBeTrue()
        ->can('read')->toBeFalse()
        ->can('missing-permission')->toBeFalse();
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');
