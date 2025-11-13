<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

interface KeyValueInterface
{
    public function getKey(): string|int;

    public function getValue(): int|float|string|bool|null;
}
