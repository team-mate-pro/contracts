<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Entity;

use DateTimeInterface;

interface TimeStampAbleInterface
{
    public function getCreatedAt(): ?DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function timestamp(): void;
}
