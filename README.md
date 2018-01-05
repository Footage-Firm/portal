<p align="center"><img src="https://i.imgur.com/bJyzdOf.png"></p>

<p align="center">
<a href="https://travis-ci.org/footage-firm/portal"><img src="https://travis-ci.org/footage-firm/portal.svg" alt="Build Status"></a>
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

## Basic Usage

...

## License

Portal is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
