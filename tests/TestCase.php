<?php

namespace Tests;

use Storyblocks\Portal\TeleportationServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TeleportationServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('portal.targets', ['foo', 'bar']);
    }
}
