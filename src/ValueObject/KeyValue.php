<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class KeyValue implements KeyValueInterface
{
    public function __construct(
        private string|int $key,
        private int|float|string|bool|null $value
    ) {
    }

    #[Groups([KeyValueInterface::class])]
    public function getKey(): string|int
    {
        return $this->key;
    }

    #[Groups([KeyValueInterface::class])]
    public function getValue(): int|float|string|bool|null
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->key . ': ' . $this->valueToString();
    }

    private function valueToString(): string
    {
        return match (true) {
            is_bool($this->value) => $this->value ? 'true' : 'false',
            is_null($this->value) => 'null',
            default => (string) $this->value,
        };
    }
}
