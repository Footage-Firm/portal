<?php

namespace Storyblocks\Portal;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
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
        $this->publishes([
            __DIR__.'/../config/portal.php' => config_path('portal.php'),
        ]);

        $this->subscribeToEvents();
    }

    private function subscribeToEvents() {
        Event::listen('*', function (string $eventName, array $data) {
            if ($this->shouldEventBeTeleported($eventName)) {
                foreach ($data as $event) {
                    $this->teleport($event);
                }
            }
        });
    }

    private function shouldEventBeTeleported(string $eventName): bool {
        return class_exists($eventName) && in_array(ShouldTeleport::class, class_implements($eventName));
    }

    public function teleport($event) {
        $targets = config('portal.targets');

        if (!is_array($targets)) {
            return;
        }

        foreach ($targets as $target) {
            EventTeleportationJob::dispatch($event)
                ->onQueue('portal-' . str_slug($target));
        }
    }

}
