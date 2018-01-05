<?php

namespace Tests;

use Storyblocks\Portal\PortalServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PortalServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('portal.targets', ['foo', 'bar']);
    }
}
