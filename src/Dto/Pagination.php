<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Dto;

use InvalidArgumentException;

final readonly class Pagination
{
    public function __construct(private int $offset = 0, private ?int $limit = 50)
    {
    }

    /**
     * @param int $page
     * @param int|null $limit - when null, no limit
     * @return self
     */
    public static function fromPage(int $page, ?int $limit = 50): self
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Page must be a positive integer');
        }

        $offset = ($page - 1) * $limit;

        return new self($offset, $limit);
    }

    public static function default(): self
    {
        return new self();
    }

    public static function withNoLimit(): self
    {
        return new self(limit: null);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
