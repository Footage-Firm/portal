<?php

namespace Storyblocks\Portal;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class TeleportationServiceProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('*', function (string $eventName, array $data) {
            if ($this->shouldEventBeTeleported($eventName)) {
                $this->teleport($eventName, $data);
            }
        });
    }

    private function shouldEventBeTeleported(string $eventName): bool {
        return class_exists($eventName) && in_array(ShouldTeleport::class, class_implements($eventName));
    }

    public function teleport(string $eventName, array $data) {
        info('Got new teleportation event: ' . $eventName . PHP_EOL . print_r($data, true));
    }

}
