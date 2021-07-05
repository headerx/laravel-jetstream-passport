# Passport and OAuth2 support for laravel's jetstream starter kit (Livewire)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/headerx/laravel-jetstream-passport/run-tests?label=tests)](https://github.com/headerx/laravel-jetstream-passport/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/headerx/laravel-jetstream-passport/Check%20&%20fix%20styling?label=code%20style)](https://github.com/headerx/laravel-jetstream-passport/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)

## Installation

1. Install Jetstream with livewire:

```bash
composer require laravel/jetstream
```

```bash
php artisan jetstream:install livewire
```

2. Install this package:


```bash
composer require headerx/laravel-jetstream-passport
```

```bash
php artisan migrate
```

```bash
php artisan jetstream-passport:install
```

3. in your user model replace the  `Laravel\Sanctum\HasApiTokens` trait with `Laravel\Passport\HasApiTokens`:
   
```diff
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
- use Laravel\Sanctum\HasApiTokens;
+ use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
...
}

```

4. in config/auth.php set the `api.driver` to `passport`:

```php
...
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],
    ],
...
```

5. replace the contents of resources/views/vendor/passport/authorize.blade.php with the following:

```blade

@include('jetstream-passport::authorize')

```

6. replace the contents of resources/views/api/index.blade.php with the following:

```blade

@include('jetstream-passport::index')

```

You can publish the views with:
```bash
php artisan vendor:publish --provider="HeaderX\JetstreamPassport\JetstreamPassportServiceProvider" --tag="jetstream-passport-views"
```


7. finally, in one of you application's service providers, add passport routes and define your tokens' scopes, and set up jetstream to use the passport scopes, example:

```php
<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // add passport routes
        Passport::routes(); 

        // define scopes for passport tokens
        Passport::tokensCan([
            'create' => 'Create resources',
            'read' => 'Read Resources',
            'update' => 'Update Resources',
            'delete' => 'Delete Resources',
        ]);

        // default scope for passport tokens
        Passport::setDefaultScope([
            // 'create',
            'read',
            // 'update',
            // 'delete',
        ]);

        // same as passport default above
        Jetstream::defaultApiTokenPermissions(['read']);

        // use passport scopes for jetstream token permissions
        Jetstream::permissions(Passport::scopeIds());
    }
}
```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [inmanturbo](https://github.com/inmanturbo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
