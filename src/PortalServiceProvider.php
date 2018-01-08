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
            if ($this->shouldEventBeTeleported($eventName)) {
                foreach ($data as $event) {
                    $this->teleport($eventName, $event);
                }
            }
        });
    }

    private function shouldEventBeTeleported(string $eventName): bool {
        return class_exists($eventName) && in_array(ShouldTeleport::class, class_implements($eventName));
    }

    public function teleport($eventName, $event) {
        $targets = Portal::getTargetsForEvent($eventName);

        if (!is_array($targets)) {
            throw new TeleportationTargetException('The teleportation targets handler needs to return an array of targets');
        }

        collect($targets)->each(function ($target) use ($event) {
            EventTeleportationJob::dispatch($event)
                ->onQueue('portal-' . $target);
        });
    }

}
