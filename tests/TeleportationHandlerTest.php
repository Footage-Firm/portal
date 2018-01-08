<?php

namespace Tests;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storyblocks\Portal\Exceptions\TeleportationTargetException;
use Storyblocks\Portal\Jobs\EventTeleportationJob;
use Storyblocks\Portal\Portal;
use Tests\Helpers\PortalEvent;

class TeleportationHandlerTest extends TestCase
{
    public function testThrowsExceptionWhenHandlerNotDefined()
    {
        $this->expectException(TeleportationTargetException::class);
        $this->expectExceptionMessageRegExp('/No teleportation target handler defined/');
        Event::fire(new PortalEvent());
    }

    public function testThrowsExceptionWhenHandlerReturnsNonArray()
    {
        Portal::setTeleportationTargetsHandler(function ($eventName) {
            return null;
        });

        $this->expectException(TeleportationTargetException::class);
        $this->expectExceptionMessageRegExp('/The teleportation targets handler needs to return an array/');
        Event::fire(new PortalEvent());
    }

    public function testAcceptsEmptyArrayOfTargets()
    {
        Bus::fake();

        Portal::setTeleportationTargetsHandler(function ($eventName) {
            return [];
        });

        Event::fire(new PortalEvent());

        Bus::assertNotDispatched(EventTeleportationJob::class);
    }
}
