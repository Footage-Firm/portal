<?php

namespace Storyblocks\Portal;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Storyblocks\Portal\Exceptions\TeleportationTargetException;
use Storyblocks\Portal\Jobs\EventTeleportationJob;

class PortalServiceProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->subscribeToEvents();
    }

    private function subscribeToEvents() {
        Event::listen('*', function (string $eventName, array $data) {
            foreach ($data as $event) {
                if ($this->shouldEventBeTeleported($eventName, $event)) {
                    $this->teleport($event);
                }
            }
        });
    }

    private function shouldEventBeTeleported(string $eventName, $event): bool {
        $isTeleportable = class_exists($eventName) && in_array(ShouldTeleport::class, class_implements($eventName));
        $eventHasBeenTeleported = isset($event->hasBeenTeleported) && $event->hasBeenTeleported === true;

        return $isTeleportable && !$eventHasBeenTeleported;
    }

    public function teleport($event) {
        $targets = Portal::getTargetsForEvent($event);

        if (!is_array($targets)) {
            throw new TeleportationTargetException('The teleportation targets handler needs to return an array of targets');
        }

        collect($targets)->each(function ($target) use ($event) {
            EventTeleportationJob::dispatch($event)
                ->onQueue('portal-' . $target);
        });
    }

}
