<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\Model;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\Model\TimeStampAbleInterface;
use TeamMatePro\Contracts\Model\TimeStampAbleTrait;

#[CoversClass(TimeStampAbleTrait::class)]
final class TimeStampAbleTraitTest extends TestCase
{
    #[Test]
    public function freshEntityHasNoTimestamps(): void
    {
        // Given: an entity that only uses the trait
        $entity = new class implements TimeStampAbleInterface {
            use TimeStampAbleTrait;
        };

        // Then: nothing has been stamped yet
        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    #[Test]
    public function firstTimestampSetsBothCreatedAtAndUpdatedAt(): void
    {
        // Given: a fresh entity
        $entity = new class implements TimeStampAbleInterface {
            use TimeStampAbleTrait;
        };

        // When: it is stamped for the first time
        $entity->timestamp();

        // Then: both dates are populated
        $createdAt = $entity->getCreatedAt();
        $updatedAt = $entity->getUpdatedAt();

        $this->assertNotNull($createdAt);
        $this->assertNotNull($updatedAt);
        $this->assertSame($createdAt->format('Y-m-d'), $updatedAt->format('Y-m-d'));
    }

    #[Test]
    public function subsequentTimestampKeepsCreatedAtUntouched(): void
    {
        // Given: an entity stamped once
        $entity = new class implements TimeStampAbleInterface {
            use TimeStampAbleTrait;
        };
        $entity->timestamp();
        $createdAt = $entity->getCreatedAt();
        $this->assertNotNull($createdAt);

        // When: it is stamped again
        $entity->timestamp();

        // Then: createdAt is preserved
        $this->assertEquals($createdAt, $entity->getCreatedAt());
    }

    #[Test]
    public function createdAtCanBeOverriddenWithAnExplicitDate(): void
    {
        // Given: an entity and a known date
        $entity = new class implements TimeStampAbleInterface {
            use TimeStampAbleTrait;
        };
        $date = new DateTimeImmutable('2020-01-15 10:30:00');

        // When: createdAt is set explicitly
        $entity->setCreatedAt($date);

        // Then: it is stored as an immutable copy of that date
        $createdAt = $entity->getCreatedAt();
        $this->assertNotNull($createdAt);
        $this->assertInstanceOf(DateTimeImmutable::class, $createdAt);
        $this->assertSame('2020-01-15 10:30:00', $createdAt->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function settingNullCreatedAtIsIgnored(): void
    {
        // Given: an entity with an already stamped createdAt
        $entity = new class implements TimeStampAbleInterface {
            use TimeStampAbleTrait;
        };
        $entity->setCreatedAt(new DateTimeImmutable('2020-01-15 10:30:00'));

        // When: null is passed
        $entity->setCreatedAt(null);

        // Then: the previous value survives
        $createdAt = $entity->getCreatedAt();
        $this->assertNotNull($createdAt);
        $this->assertSame('2020-01-15', $createdAt->format('Y-m-d'));
    }
}
