# Passport and OAuth2 support for Laravel's Jetstream starter kit (Livewire)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)

[![Total Downloads](https://img.shields.io/packagist/dt/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)

## Installation

1. Install Jetstream with livewire:

Using Laravel installer

```bash
laravel new jetpass-app --stack=livewire --jet --dark --api --dev
```

Using Composer

```bash
composer require laravel/jetstream
```

```bash
php artisan jetstream:install livewire --api
```

See [Docs](https://jetstream.laravel.com/installation.html) for more info.

1. Install this package:


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

7. finally, in one of you application's service providers, add passport routes and define scopes for your passport tokens, and set up jetstream to use them, example:

```php
<?php
// app/Providers/JetstreamServiceProvider
namespace App\Providers;

// ...
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Laravel\Passport\Passport;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        // ...
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {

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

8. Now you can also edit `routes/api.php`

```diff
<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
-})->middleware(Authenticate::using('sanctum'));
+})->middleware('auth:api');
```

9. Lastly, for postcss processing of styles, you can edit tailwind.config.js to include '.vendor/headerx/laravel-jetstream-passport/resources/views/**/*.blade.php'

```js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/headerx/laravel-jetstream-passport/resources/views/**/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
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
