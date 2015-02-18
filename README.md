# Laravel Queue Clear Command

[![Build Status](https://travis-ci.org/morrislaptop/laravel-queue-clear.png?branch=master)](https://travis-ci.org/morrislaptop/laravel-queue-clear)

Often, when you're issuing `php artisan db:refresh --seed`, you will have queue jobs left over that won't match with
your database records anymore.

This package simplifies the process of clearing your queues drastically.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
		"morrislaptop/laravel-queue-clear": "~1.0"
	}
}
```

And then include the service provider within config/app.php.

```php
'providers' => [
    'Morrislaptop\LaravelQueueClear\LaravelQueueClearServiceProvider'
];
```

## Usage

```bash
php artisan queue:clear [connection] [queue]
```

Where:

* `[connection]` is the name of a connection in your `config/queue.php`
* `[queue]` is the name of the queue / pipe you want to clear

If you omit either argument, it will use your default driver and the default queue / pipe for that driver.
