<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\EventPublisher;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\EventPublisher\EventsAwareInterface;
use TeamMatePro\Contracts\EventPublisher\EventsAwareTrait;
use TeamMatePro\Contracts\EventPublisher\EventsPublisherInterface;
use TeamMatePro\Contracts\EventPublisher\NullEventPublisher;

#[CoversClass(NullEventPublisher::class)]
final class NullEventPublisherTest extends TestCase
{
    #[Test]
    public function itImplementsEventsPublisherInterface(): void
    {
        $publisher = new NullEventPublisher();

        $this->assertInstanceOf(EventsPublisherInterface::class, $publisher);
    }

    #[Test]
    public function itPublishesWithoutThrowingException(): void
    {
        $publisher = new NullEventPublisher();
        $entity = $this->createEntityWithEvents(2);

        $publisher->publish($entity);

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itPullsEventsFromEntity(): void
    {
        $publisher = new NullEventPublisher();
        $entity = $this->createEntityWithEvents(3);

        $this->assertCount(3, $entity->pullEvents()); // Events exist before publish

        $entity = $this->createEntityWithEvents(3); // Recreate to have events again
        $publisher->publish($entity);

        $this->assertEmpty($entity->pullEvents()); // Events are pulled and cleared
    }

    #[Test]
    public function itHandlesMultipleEntities(): void
    {
        $publisher = new NullEventPublisher();
        $entity1 = $this->createEntityWithEvents(2);
        $entity2 = $this->createEntityWithEvents(3);
        $entity3 = $this->createEntityWithEvents(1);

        $publisher->publish($entity1, $entity2, $entity3);

        $this->assertEmpty($entity1->pullEvents());
        $this->assertEmpty($entity2->pullEvents());
        $this->assertEmpty($entity3->pullEvents());
    }

    #[Test]
    public function itHandlesEntityWithNoEvents(): void
    {
        $publisher = new NullEventPublisher();
        $entity = $this->createEntityWithEvents(0);

        $publisher->publish($entity);

        $this->assertEmpty($entity->pullEvents());
    }

    #[Test]
    public function itHandlesEmptyEntityList(): void
    {
        $publisher = new NullEventPublisher();

        $publisher->publish();

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itPublishesEventWithoutThrowingException(): void
    {
        $publisher = new NullEventPublisher();
        $event = new \stdClass();
        $event->name = 'test-event';

        $publisher->publishEvent($event);

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itPublishesMultipleEvents(): void
    {
        $publisher = new NullEventPublisher();

        $event1 = new \stdClass();
        $event1->name = 'event1';

        $event2 = new \stdClass();
        $event2->name = 'event2';

        $event3 = new \stdClass();
        $event3->name = 'event3';

        $publisher->publishEvent($event1, $event2, $event3);

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itPublishesEventWithNoEvents(): void
    {
        $publisher = new NullEventPublisher();

        $publisher->publishEvent();

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itHandlesDifferentEventTypes(): void
    {
        $publisher = new NullEventPublisher();

        $stdEvent = new \stdClass();
        $arrayObjectEvent = new \ArrayObject(['key' => 'value']);
        $customEvent = new class {
            public string $eventName = 'custom';
        };

        $publisher->publishEvent($stdEvent, $arrayObjectEvent, $customEvent);

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itDoesNotActuallyPublishEvents(): void
    {
        $publisher = new NullEventPublisher();
        $entity = $this->createEntityWithEvents(5);

        // Verify events exist
        $this->assertCount(5, $entity->pullEvents());

        // Recreate entity
        $entity = $this->createEntityWithEvents(5);

        // Publish should only pull events, not dispatch them anywhere
        $publisher->publish($entity);

        // Events should be cleared but not dispatched
        $this->assertEmpty($entity->pullEvents());
    }

    #[Test]
    public function itHandlesMixedPublishCalls(): void
    {
        $publisher = new NullEventPublisher();

        $entity1 = $this->createEntityWithEvents(2);
        $entity2 = $this->createEntityWithEvents(0);

        $event1 = new \stdClass();
        $event1->name = 'direct-event';

        // Mix of publish and publishEvent calls
        $publisher->publish($entity1, $entity2);
        $publisher->publishEvent($event1);
        $publisher->publish();
        $publisher->publishEvent();

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itCanBeCalledMultipleTimes(): void
    {
        $publisher = new NullEventPublisher();

        for ($i = 0; $i < 10; $i++) {
            $entity = $this->createEntityWithEvents(2);
            $publisher->publish($entity);
        }

        for ($i = 0; $i < 10; $i++) {
            $event = new \stdClass();
            $event->id = $i;
            $publisher->publishEvent($event);
        }

        $this->assertTrue(true); // No exception thrown
    }

    /**
     * Helper method to create an entity with specified number of events
     */
    private function createEntityWithEvents(int $count): EventsAwareInterface
    {
        $entity = new class implements EventsAwareInterface {
            use EventsAwareTrait;

            public function addEvent(object $event): void
            {
                $this->events[] = $event;
            }
        };

        for ($i = 0; $i < $count; $i++) {
            $event = new \stdClass();
            $event->id = $i;
            $entity->addEvent($event);
        }

        return $entity;
    }
}
