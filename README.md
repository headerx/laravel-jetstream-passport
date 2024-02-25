# Passport and OAuth2 support for Laravel's Jetstream starter kit (Livewire)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/headerx/laravel-jetstream-passport/test-stubs.yml?branch=main&label=tests&style=flat-square)](https://github.com/headerx/laravel-jetstream-passport/actions?query=workflow%3Atest-stubs+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/headerx/laravel-jetstream-passport/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/headerx/laravel-jetstream-passport/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/headerx/laravel-jetstream-passport.svg?style=flat-square)](https://packagist.org/packages/headerx/laravel-jetstream-passport)

## Installation

1. Install Jetstream with livewire:

Using Laravel installer

```bash
laravel new jetpass-app --stack=livewire --jet --dark --api --pest --dev
```

Using Composer

```bash
composer require "laravel/jetstream:^5.x-dev"
```

```bash
php artisan jetstream:install livewire --api --dark --pest
```

See [Docs](https://jetstream.laravel.com/installation.html) for more info.

2. Install this package:


```bash
composer require "headerx/laravel-jetstream-passport:^1.0"
```

```bash
php artisan jetstream-passport:install
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
