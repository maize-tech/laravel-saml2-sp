# Laravel SAML2 Service Provider

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-saml2-sp.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-saml2-sp)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-saml2-sp/run-tests?label=tests)](https://github.com/maize-tech/laravel-saml2-sp/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-saml2-sp/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/maize-tech/laravel-saml2-sp/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-saml2-sp.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-saml2-sp)

Easily add SAML2 authentication support within your application. 

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-saml2-sp
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="saml2-sp-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="saml2-sp-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$saml2SP = new Maize\Saml2Sp();
echo $saml2SP->echoPhrase('Hello, Maize!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Riccardo Dalla Via](https://github.com/riccardodallavia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
