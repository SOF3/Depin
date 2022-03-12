<?php

declare(strict_types=1);

namespace SOFe\Depin;

/**
 * Subscribes to new objects for tracking.
 */
interface Tracker {
    /**
     * Notifies the tracker of a new object.
     */
    public function track(Scope $scope, object $object) : void;
}
