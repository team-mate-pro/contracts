<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\EventPublisher;

trait EventsAwareTrait
{
    /** @var object[] */
    private array $events = [];

    /** @return object[] */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
