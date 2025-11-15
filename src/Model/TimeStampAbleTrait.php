<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimeStampAbleTrait
{
    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;
    #[Groups([TimeStampAbleInterface::class])]
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    #[Groups([TimeStampAbleInterface::class])]
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function timestamp(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        if (!$createdAt) {
            return;
        }
        $this->createdAt = DateTimeImmutable::createFromInterface($createdAt);
    }
}
