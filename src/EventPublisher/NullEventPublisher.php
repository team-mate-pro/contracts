<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\EventPublisher;

final class NullEventPublisher implements EventsPublisherInterface
{
    public function publish(EventsAwareInterface ...$entity): void
    {
        foreach ($entity as $e) {
            foreach ($e->pullEvents() as $event) {
                // just pull
            }
        }
    }

    public function publishEvent(object ...$event): void
    {
    }
}
