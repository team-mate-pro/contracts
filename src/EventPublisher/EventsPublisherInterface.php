<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\EventPublisher;

interface EventsPublisherInterface
{
    /**
     * Iterates through all defined event's and dispatches them to the event bus with appropriate stamps if needed.
     * Event should be removed from the model once pulled.
     */
    public function publish(EventsAwareInterface ...$entity): void;

    public function publishEvent(object ...$event): void;
}
