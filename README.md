<p align="center"><img src="https://i.imgur.com/bJyzdOf.png"></p>

<p align="center">
<a href="https://travis-ci.org/Footage-Firm/portal"><img src="https://travis-ci.org/Footage-Firm/portal.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/footage-firm/portal"><img src="https://poser.pugx.org/footage-firm/portal/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/footage-firm/portal"><img src="https://poser.pugx.org/footage-firm/portal/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/footage-firm/portal"><img src="https://poser.pugx.org/footage-firm/portal/license.svg" alt="License"></a>
</p>

## Introduction

Portal allows you to dispatch Laravel events across multiple apps with ease.

## Installation

To get started with Portal, simply run:

    composer require footage-firm/portal

If you are using Laravel 5.5+, there is no need to manually register the service provider. However, if you are using an earlier version of Laravel, register the `PortalServiceProvider` in your `app` configuration file:

```php
'providers' => [
    // Other service providers...

    Storyblocks\Portal\PortalServiceProvider::class,
],
```

> The package needs to be installed for all projects that need to be able to send or receive events

## How it works

All portal-connected Laravel apps will need to use a central queue for transmitting events, so make sure that [queueing](https://laravel.com/docs/5.5/queues) is configured, and that all connected Laravel apps are using the same queue.

### Configuring queues

>**Warning:** Before you continue, make sure that your default `QUEUE_DRIVER` in `config/queue.php` is NOT set to `sync`, as this will cause an infinite loop for teleported events

Each application that receives events, will need to have a queue and queue worker configured to listen for portal events.

If you're using the [default laravel queueing system](https://laravel.com/docs/5.5/queues#running-the-queue-worker), this can be done by running a worker with the `--queue` parameter like so:

    php artisan queue:work redis --queue=portal-myapp

If you're using [Laravel Horizon](https://laravel.com/docs/5.5/horizon), you will simply need to update the environments configuration, by adding an additional queue to the array like so:

```
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default', 'portal-myapp'],
            'balance' => 'simple',
            'processes' => 10,
            'tries' => 3,
        ],
    ],
    ...
```

>**Note:** Queue names need to always be prefixed with `portal-`

### Configuring event targets

When a teleportation-eligible event is fired, a custom handler will be executed. This handler is responsible for returning the targets that the events are sent to.

This callback can be configured anywhere you like, but a good starting place would be the `boot()` function in your `AppServiceProvider`

Here's a couple of examples

```php
// Teleport events to a static list of apps
Portal::setTeleportationTargetsHandler(function ($eventName) {
    return [
        'myapp' // Note: This is the same name as specified in the queue worker above (but without the "portal-" prefix)
    ];
});

// Teleport events to a database-defined list of targets
Portal::setTeleportationTargetsHandler(function ($eventName) {
    return Server::where('is_active', 1)
        ->where('hostname', '!=', gethostname()) // Exclude ourselves
        ->lists('hostname');
});

// Send certain events to certain targets
Portal::setTeleportationTargetsHandler(function ($eventName) {
    if ($eventName === 'SpecialEvent') {
        return [
            'myapp',
            'specialapp'
        ];
    } else {
        return [
            'myapp';
        ];
    }
});
```

## Basic Usage


### Sending portal events

Create your [Laravel Events](https://laravel.com/docs/5.5/events) as usual, but make sure to implement the `ShouldTeleport` interface.

**Example:**

```php
<?php

namespace App\Events;

use Storyblocks\Portal\ShouldTeleport;

class OrderShipped implements ShouldTeleport
{
}
```

The event can triggered as per usual:

```php
event(new OrderShipped());
```

The event will automatically be queued up and teleported to any other Portal-enabled Laravel app.


### Receiving portal events

Simply define an event listener in `EventServiceProvider` as you usually would with a Laravel app.

**Example:**
```php
protected $listen = [
    'App\Events\OrderShipped' => [
        'App\Listeners\SendShipmentNotification',
    ],
];
```

## License

Portal is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
