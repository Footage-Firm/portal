<?php

namespace Tests;

use Storyblocks\Portal\Portal;
use Storyblocks\Portal\PortalServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function setUp() {
        parent::setUp();

        Portal::reset();
    }

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
