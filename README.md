A Laravel >= 5.6 utility package to enable developers to log in as other users during development.


## Installation
To install the package, simply follow the steps below.

Install the package using Composer:

```
$ composer require tungltdev/laravel-faker-sudo
```

Add line to file .env to configure toggles function

```php
FAKER_USER_ENABLED=true
```

Finally, publish the package's assets (the package won't work without this):

```
$ php artisan vendor:publish --provider="Tungltdev\fakerUserSudoSu\ServiceProvider" --force
```

Include the partial in your layout file.

```php
@if(config('faker_user.enabled'))
    @includeIf('faker_user::user-selector')
@endif
```

## Config
After running `vendor:publish`, a config file called `faker_user.php` should appear in your project. Within here, there are two configuration values:

**faker_user.allowed_tlds `array`**

By default, the package will disable itself on any domains that don't have a TLD of `.dev` or `.local`. This is a security measure to reduce the risk of accidentally enabling the package in production. If you have a different TLD in development, you can edit the config option `faker_user.allowed_tlds`.

**faker_user.user_model `string`**

The path to the application User model. This will be used to retrieve the users displayed in the select dropdown. This must be an Eloquent Model instance. This is set to `App\Models\User` by default.


## Disclaimer - DANGER!
This package can pose a serious security issue if used incorrectly, as anybody will be able to take control of any user's account. Please ensure that the service provider is only registered when the app is in a debug/local environment.

By default, the package will disable itself on any domains that don't have a TLD of `.dev` or `.local`. This is a security measure to reduce the risk of accidentally enabling the package in production. If you have a different TLD in development, you can edit the config option `faker_user.allowed_tlds`.

By using this package, you agree that VIA Creative and the contributors of this package cannot be held responsible for any damages caused by using this package.
