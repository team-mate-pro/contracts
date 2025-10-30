<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\EventPublisher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\EventPublisher\EventsAwareInterface;
use TeamMatePro\Contracts\EventPublisher\EventsAwareTrait;

#[CoversClass(EventsAwareTrait::class)]
final class EventsAwareTraitTest extends TestCase
{
    #[Test]
    public function itReturnsEmptyArrayWhenNoEventsAreAdded(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;
        };

        $events = $entity->pullEvents();

        $this->assertIsArray($events);
        $this->assertEmpty($events);
    }

    #[Test]
    public function itStoresAndReturnsEvents(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $event1 = new \stdClass();
        $event1->name = 'event1';
        $event2 = new \stdClass();
        $event2->name = 'event2';

        $entity->addEvent($event1);
        $entity->addEvent($event2);

        $events = $entity->pullEvents();

        $this->assertCount(2, $events);
        $this->assertSame($event1, $events[0]);
        $this->assertSame($event2, $events[1]);
    }

    #[Test]
    public function itClearsEventsAfterPulling(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $event1 = new \stdClass();
        $event1->name = 'event1';
        $event2 = new \stdClass();
        $event2->name = 'event2';

        $entity->addEvent($event1);
        $entity->addEvent($event2);

        $firstPull = $entity->pullEvents();
        $this->assertCount(2, $firstPull);

        $secondPull = $entity->pullEvents();
        $this->assertEmpty($secondPull);
    }

    #[Test]
    public function itHandlesMultiplePullCycles(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        // First cycle
        $event1 = new \stdClass();
        $event1->name = 'event1';
        $entity->addEvent($event1);
        $firstPull = $entity->pullEvents();
        $this->assertCount(1, $firstPull);
        $this->assertSame($event1, $firstPull[0]);

        // Second cycle
        $event2 = new \stdClass();
        $event2->name = 'event2';
        $entity->addEvent($event2);
        $secondPull = $entity->pullEvents();
        $this->assertCount(1, $secondPull);
        $this->assertSame($event2, $secondPull[0]);

        // Third cycle - empty
        $thirdPull = $entity->pullEvents();
        $this->assertEmpty($thirdPull);
    }

    #[Test]
    public function itHandlesDifferentEventTypes(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $stdEvent = new \stdClass();
        $stdEvent->name = 'std';

        $arrayObjectEvent = new \ArrayObject(['key' => 'value']);

        $customEvent = new class {
            public string $eventName = 'custom';
        };

        $entity->addEvent($stdEvent);
        $entity->addEvent($arrayObjectEvent);
        $entity->addEvent($customEvent);

        $events = $entity->pullEvents();

        $this->assertCount(3, $events);
        $this->assertInstanceOf(\stdClass::class, $events[0]);
        $this->assertInstanceOf(\ArrayObject::class, $events[1]);
        /** @var object{eventName: string} $event */
        $event = $events[2];
        $this->assertSame('custom', $event->eventName);
    }

    #[Test]
    public function itMaintainsEventOrder(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $events = [];
        for ($i = 1; $i <= 5; $i++) {
            $event = new \stdClass();
            $event->order = $i;
            $events[] = $event;
            $entity->addEvent($event);
        }

        $pulledEvents = $entity->pullEvents();

        $this->assertCount(5, $pulledEvents);
        for ($i = 0; $i < 5; $i++) {
            /** @var object{order: int} $event */
            $event = $pulledEvents[$i];
            $this->assertSame($i + 1, $event->order);
        }
    }

    #[Test]
    public function itAllowsReAddingEventsAfterPulling(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $event1 = new \stdClass();
        $event1->name = 'event1';
        $entity->addEvent($event1);

        $firstPull = $entity->pullEvents();
        $this->assertCount(1, $firstPull);

        // Re-add the same event object
        $entity->addEvent($event1);
        $secondPull = $entity->pullEvents();
        $this->assertCount(1, $secondPull);
        $this->assertSame($event1, $secondPull[0]);
    }

    #[Test]
    public function itHandlesLargeNumberOfEvents(): void
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        $eventCount = 1000;
        for ($i = 0; $i < $eventCount; $i++) {
            $event = new \stdClass();
            $event->id = $i;
            $entity->addEvent($event);
        }

        $events = $entity->pullEvents();

        $this->assertCount($eventCount, $events);
        /** @var object{id: int} $firstEvent */
        $firstEvent = $events[0];
        /** @var object{id: int} $lastEvent */
        $lastEvent = $events[$eventCount - 1];
        $this->assertSame(0, $firstEvent->id);
        $this->assertSame($eventCount - 1, $lastEvent->id);
    }
}
