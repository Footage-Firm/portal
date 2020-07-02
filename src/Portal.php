<?php

namespace Storyblocks\Portal;

use Storyblocks\Portal\Exceptions\TeleportationTargetException;
use Illuminate\Support\Facades\Event;

class Portal
{
    /**
     * @var callable|null
     */
    private static $teleportationTargetsHandler = null;

    public static function setTeleportationTargetsHandler(?callable $callback) {
        self::$teleportationTargetsHandler = $callback;
    }

    public static function reset() {
        self::setTeleportationTargetsHandler(null);
    }

    public static function getTargetsForEvent(Event $event) {
        if (!self::$teleportationTargetsHandler) {
            throw new TeleportationTargetException('No teleportation target handler defined. Please define one using Portal::setTeleportationTargetsHandler(function ($eventName) { })');
        }

        $handler = self::$teleportationTargetsHandler;

        return $handler($event);
    }

}
