# Laravel Jira

This Laravel package provides an interface for using Jira through its **API**.


## Installation

The Laravel package can be installed via [Composer](http://getcomposer.org) by requiring the
`capturehighered/laravel-jira` package in your project's `composer.json`.

```json
{
    "require": {
        "capturehighered/laravel-jira": "~1.0"
    }
}
```

And running a composer update from your terminal:
```sh
php composer.phar update
```

To use the Jira Package, you must register the provider when bootstrapping your Laravel 5 application.

Find the `providers` key in your `config/app.php` and register the AWS Service Provider.

```php
    'providers' => array(
        // ...
        CaptureHigherEd\LaravelJira\Providers\IntegrationServiceProvider::class,
    )
```

## Configuration

By default, the package uses the following environment variables:
```
JIRA_API_EMAIL
JIRA_API_TOKEN
```

You must publish the config file for this to work:

```sh
php artisan vendor:publish
```

## License

This software is licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Versioning

This project follows the [Semantic Versioning](http://semver.org/)

## Thanks

Code originated by https://github.com/ashish-negi