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


## Receiving portal events

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
