<?php

namespace HeaderX\JetstreamPassport;

use HeaderX\JetstreamPassport\Commands\JetstreamPassportCommand;
use HeaderX\JetstreamPassport\Http\Livewire\ApiTokenManager;
use HeaderX\JetstreamPassport\Http\Livewire\OAuthClientManager;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class JetstreamPassportServiceProvider extends PackageServiceProvider
{
    public function bootingPackage(): void
    {
        Blade::component('jetstream-passport::components.form-help-text', 'jetpass-form-help-text');
        Blade::component('jetstream-passport::components.textarea', 'jetpass-textarea');
        Livewire::component('jetstream-passport.api-token-manager', ApiTokenManager::class);
        Livewire::component('jetstream-passport.oauth-client-manager', OAuthClientManager::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-jetstream-passport')
            ->hasViews()
            ->hasCommand(JetstreamPassportCommand::class);
    }
}
