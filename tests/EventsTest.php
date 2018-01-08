<?php

namespace Tests;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storyblocks\Portal\Jobs\EventTeleportationJob;
use Storyblocks\Portal\Portal;
use Tests\Helpers\PortalEvent;
use Tests\Helpers\RegularEvent;

class EventsTest extends TestCase
{

    protected function setUp() {
        parent::setUp();

        Portal::setTeleportationTargetsHandler(function ($eventName) {
            return ['dummy-target'];
        });
    }

    public function testRegularEventsAreNotTeleported()
    {
        Bus::fake();

        Event::fire(new RegularEvent());

        Bus::assertNotDispatched(EventTeleportationJob::class);
    }

    public function testPortalEventsAreTeleported()
    {
        Bus::fake();

        $event = new PortalEvent();
        Event::fire($event);

        Bus::assertDispatched(EventTeleportationJob::class, function ($job) use ($event) {
            return $job->event === $event;
        });
    }
}
