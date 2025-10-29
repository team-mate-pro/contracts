<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\EventPublisher;

interface EventsAwareInterface
{
    /**
     * Should return an array of objects that represent events and remove them from the entity.
     * @return object[]
     */

    public function pullEvents(): array;
}
